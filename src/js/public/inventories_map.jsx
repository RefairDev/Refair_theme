'use strict';
import React, { useState, useEffect } from 'react';
import ReactDom, { render } from 'react-dom';
import { useMapEvent, MapContainer, TileLayer, Marker, useMap, FeatureGroup, Polygon } from 'react-leaflet';
// import MarkerClusterGrp from "./markerClusterGroup.jsx";
import eventBus from "./eventBus.jsx";
import 'leaflet/dist/leaflet.css';
import 'leaflet/dist/images/marker-icon.png';
import 'leaflet/dist/images/marker-icon-2x.png';
import { GestureHandling } from "leaflet-gesture-handling";
import "leaflet-gesture-handling/dist/leaflet-gesture-handling.css";

export const MapController = () => {
    const map = useMap();
    
    useEffect(() => {
      map.addHandler("gestureHandling", GestureHandling);
      // @ts-expect-error typescript does not see additional handler here
      map.gestureHandling.enable();
    }, [map]);
  
    return null;
  };
export const UnclickComponent = ({unclick}) => {
    const map = useMapEvent('click', (e) => {
        
        if (e.originalEvent.srcElement.classList.contains('leaflet-container') ){
            unclick();
        }
    })
    return null;

  };


class DepositsMap extends React.Component {

    constructor(props) {
        super(props);
        let cityList = JSON.parse(geojson.iris);
        let citiesOutlines = JSON.parse(geojson.outlines);
        this.state = { "deposits": [], "isNewDeposits": false, cities: cityList, outlines: citiesOutlines, highlightedMarker: React.createRef() };

        this.highlightDeposit = this.highlightDeposit.bind(this);
        this.hightlightMarker = this.hightlightMarker.bind(this);
        this.unclick = this.unclick.bind(this);
        this.updateMap = this.updateMap.bind(this);
        this.updateDeposits = this.updateDeposits.bind(this);
    }

    componentDidMount() {
        this.fetchDeposits();
        eventBus.on("mapResize", this.updateMap);
        //eventBus.on("depositsUpdate", this.updateDeposits);
    }

    componentDidUpdate() {

    }

    fetchDeposits() {

        let providerId = this.getProviderReference();

        let url = admin_objects.rest_url + 'deposit/?per_page=100';

        if ('0' !== providerId){
            url = url + '&deposit_type='+ providerId;
        }

        fetch(url)
            .then(response => response.json())
            .then(myJSON => {
                let newState = this.state;
                myJSON.forEach((elt, idx) => {
                    newState.deposits.push(elt);
                });
                newState.deposits.sort(function (a, b) {
                    if (a.availability > b.availability) return 1;
                    if (a.availability < b.availability) return -1;
                    return 0;
                });
                this.setState(newState);
                //eventBus.dispatch("depositUpdate", newState.deposits);
                //document.getElementById('materials-loader').classList.remove("show");

            });
    }

    updateDeposits(deposits){
        this.setState( {"deposits": deposits });
    }

    getProviderReference() {
        let parentReference = "0";
        let parentReferenceNode = document.querySelector("article.provider");
        if (null !== parentReferenceNode) {
            parentReference = parentReferenceNode.getAttribute('data-provider-id');
        }
        return parentReference;
    }

    highlightDeposit(id) {
        eventBus.dispatch("depositHighLight", id);
        this.setState({ markerHighlight: id, highlightedMarker: React.createRef() });
    }

    unclick(){
        eventBus.dispatch("depositHighLight", null);
        this.setState({ markerHighlight: 0, highlightedMarker: React.createRef() });
    }

    hightlightMarker(id) {
        // if (this.clusterGroup.current != null){
        //     if (this.state.highlightedMarker.current !=null){
        //     let clusterNode = this.clusterGroup.current.getVisibleParent(this.state.highlightedMarker.current);
        //     clusterNode.unspiderfy();
        //     }

        // }
        this.setState({ markerHighlight: id });

    }

    updateMap(sideBarState) {
        this.setState({ 'sideBarState': sideBarState });
    }

    render() {
        const position = [44.838410, -0.598461];
        let centroidsPoints = [];
        let irisPolygons = [];
        this.state.deposits.forEach(elt => {

            let inIris = [];
            inIris = this.state.cities.filter(city => (city.CODE_IRIS.includes(elt.iris)) ? true : false);

            if ( 0 < inIris.length){

                let centroidIdx = centroidsPoints.findIndex((elt => elt.location.NOM_COM === inIris[0].NOM_COM))
    
                if (centroidIdx > -1) {
                    centroidsPoints[centroidIdx].count++;
                } else {
                    if ( elt.city.length > 0 ){
                        centroidsPoints.push({ 'location': inIris[0], 'count': 1, 'id': elt.city[0] });
                    }else{
                        centroidsPoints.push({ 'location': inIris[0], 'count': 1, 'id': inIris[0].NOM_COM });
                    }
                }
            }
        });

        let markers = centroidsPoints.map((pt, idx) => {

            let cmpMarker = (<></>);
            let iconArgs = {
                 iconSize: [30, 30], // [30, 30],
                 iconAnchor: [15, 15], //13,13
                // popupAnchor: [13, 13],
                // shadowSize: [0, 0],
                // shadowAnchor: [0, 0]
            }

            iconArgs['className'] = 'default-site-marker';
            iconArgs['html'] = `<div><span>${pt.count}</span>`;

            
            
            let polygonPoints = this.state.outlines[pt['location']['NOM_COM']]['coords'];
            let pathOptions = { 
                'stroke': true,
                'color': geojson.styles.default.stroke,
                'opacity': geojson.styles.default.str_opacity,
                'fill': true,
                'fillColor': geojson.styles.default.fill,
                'fillOpacity': geojson.styles.default.fill_opacity
             };
            
            if (this.state.markerHighlight == pt.id) {
                iconArgs['className'] = 'default-site-marker-highlighted';


                pathOptions['stroke'] = true;
                pathOptions['color'] = geojson.styles.highlight.stroke;
                pathOptions['opacity'] = geojson.styles.highlight.str_opacity;
                pathOptions['fill'] = true;
                pathOptions['fillColor'] = geojson.styles.highlight.fill;
                pathOptions['fillOpacity'] = geojson.styles.highlight.fill_opacity;

            };

            let markerIcon = L.divIcon(iconArgs);

            cmpMarker = (
                <FeatureGroup key={pt.id}  eventHandlers={{
                    click: (e) => {this.highlightDeposit(pt.id);},
                }}>
                    <Marker
                        key={pt.id+'-m'}
                        position={this.state.outlines[pt['location']['NOM_COM']]['center']}
                        icon={markerIcon}

                    />
                    <Polygon key={pt.id+'-pl'} pathOptions={pathOptions} positions={polygonPoints} />

                </FeatureGroup>
            )

            if (this.state.markerHighlight == pt.id) {

                cmpMarker = (
                    <FeatureGroup key={pt.id} eventHandlers={{
                        click: () =>{ this.highlightDeposit(pt.id);},
                    }}>
                        <Marker
                            position={this.state.outlines[pt['location']['NOM_COM']]['center']}
                            icon={markerIcon}
                        />
                        <Polygon pathOptions={pathOptions} positions={polygonPoints} />

                    </FeatureGroup>
                );



            }

            return cmpMarker;

        });
        return <MapContainer center={position} zoom={12} minZoom={11} maxZoom={14} scrollWheelZoom={true} maxBounds={[[44.71212365, -0.90881398], [45.04816398, -0.44274442]]}>
            <MapController />
            <UnclickComponent unclick={this.unclick} />
            <TileLayer
                attribution='&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                url={map.url + "/{z}/{x}/{y}.png"}
            />
            {markers.length > 0 && markers}
            <MapUpdater currentOpening={this.state.sideBarState} />
        </MapContainer>

    }
}

function MapUpdater(currentOpening) {
    const map = useMap();
    const [openState, setOpenState] = useState(true);
    if (openState != currentOpening) {
        setOpenState(currentOpening);
        map.invalidateSize(true);
    }

    return null;
}

window.addEventListener('load', () => {
    // Render the app inside our shortcode's #app div
    if (document.getElementById('maplf-deposits') != null) {
        ReactDom.render(
            <DepositsMap />,
            document.getElementById('maplf-deposits'));
    }

});