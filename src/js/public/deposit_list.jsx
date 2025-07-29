'use strict';
import React from 'react';
import ReactDom from 'react-dom';
import eventBus from "./eventBus.jsx";
import { ReactNotifications, Store } from 'react-notifications-component';
import parse from 'html-react-parser';
import 'react-notifications-component/dist/theme.css';
import Select from 'react-select';

// const eventBus = {
//     on(event, callback) {
//       document.addEventListener(event, (e) => callback(e.detail));
//     },
//     dispatch(event, data) {
//       document.dispatchEvent(new CustomEvent(event, { detail: data }));
//     },
//     remove(event, callback) {
//       document.removeEventListener(event, callback);
//     },
//   };

Array.prototype.equals = function (arr2) {
    return (
        this.length === arr2.length &&
        this.every((value, index) => value === arr2[index])
    );
};

class Deposits extends React.Component {

    constructor(props) {
        super(props);
        let provider = this.getProviderReference();
        this.state = { sortingReady: false, filteringReady: false, "deposits": [], "deposit_types": [], "offset": 0, "loadedIdx": 0, "isloading": false, "canLoadMore": true, filters: {}, "order": 'asc', "orderby": "title", "ordertype": "prop" }

        this.fetchDeposits = this.fetchDeposits.bind(this);
        this.fetchDepositsTypes = this.fetchDepositsTypes.bind(this);
        this.loadMore = this.loadMore.bind(this);
        this.onUpdateFilters = this.onUpdateFilters.bind(this);
        this.onUpdateSorting = this.onUpdateSorting.bind(this);
        this.onFilteringReady = this.onFilteringReady.bind(this);
        this.onSortingReady = this.onSortingReady.bind(this);
    }

    componentDidMount() {
        this.fetchDepositsTypes();
        eventBus.on("updateFilters", this.onUpdateFilters);
        eventBus.on("updateSorting", this.onUpdateSorting);
        eventBus.on('filteringReady', this.onFilteringReady);
        eventBus.on('sortingReady', this.onSortingReady);
        //this.fetchDeposits();
    }

    componentDidUpdate() {
        if (!this.state.isloading) {
            let matList = document.querySelector(".deposits-list");
            let matCard = document.querySelector(".deposits-list .deposit-card:not(.hidden)");
            // if ( null != matCard ){
            //     let matCardStyles = getComputedStyle(matCard)
            //     let nbPerLines = Math.round(matList.offsetWidth/ (matCard.offsetWidth + parseInt(matCardStyles.marginRight)*2));
            //     let lines = Math.ceil(matList.children.length / nbPerLines);
            //     let totalHeight = lines*(matCard.offsetHeight+parseInt(matCardStyles.marginTop)+parseInt(matCardStyles.marginBottom)+2)+4;
            //     //matList.style.maxHeight= totalHeight.toString()+"px";
            //     matList.style.maxHeight= "10000px";
            // }

        }

    }

    onUpdateFilters(newFilters) {

        this.resetDeposits();

        this.setState({ filters: newFilters });
        let sorting = { "order": this.state.order, "orderby": this.state.orderby, "ordertype": this.state.ordertype }
        this.fetchDeposits(newFilters, sorting);
    }

    onUpdateSorting(rawSorting) {
        let { order, orderby, ordertype } = this.state;

        let sortings = rawSorting['sort'].split("-");
        ordertype = sortings[0];
        orderby = sortings[1];
        order = rawSorting['order'];

        let sorting = { "ordertype": ordertype, "orderby": orderby, "order": order };
        let newState = this.state;
        newState.ordertype = ordertype;
        newState.orderby = orderby;
        newState.order = order;
        this.setState(newState);

        this.resetDeposits();
        this.fetchDeposits(this.state.filters, sorting);

    }

    onFilteringReady() {
        this.setState({ filteringReady: true });
        if (true === this.state.sortingReady) {
            let sorting = { "order": this.state.order, "orderby": this.state.orderby, "ordertype": this.state.ordertype }
            this.fetchDeposits(this.state.filters, sorting);
        }

    }

    onSortingReady() {
        let newState = this.state;
        newState.sortingReady = true;
        this.setState(newState);
        if (true === this.state.filteringReady) {
            let sorting = { "order": this.state.order, "orderby": this.state.orderby, "ordertype": this.state.ordertype }
            this.fetchDeposits(this.state.filters, sorting);
        }
    }


    resetDeposits() {
        let newState = this.state;
        newState.offset = 1;
        newState.loadedIdx = 0;
        newState.deposits = [];
        this.setState(newState);
    }

    getProviderReference() {
        let parentReference = "0";
        let parentReferenceNode = document.querySelector("article.provider");
        if (null !== parentReferenceNode) {
            parentReference = parentReferenceNode.getAttribute('data-provider-id');
        }
        return parentReference;
    }

    fetchDeposits(filters, sorting) {

        if (!this.state.filteringReady || !this.state.sortingReady) {
            return;
        }

        filters = (filters) ? filters : this.state.filters;

        if (!sorting) {
            sorting = [];
            sorting["order"] = this.state.order;
            sorting['orderby'] = this.state.orderby;
        }
        // sorting = (sorting) ? sorting : {"order":this.state.order,"orderby":this.state.orderby,"ordertype":this.state.ordertype};
        let offset = this.state.offset;
        //document.getElementById('deposits-loader').classList.add("show");
        let url = admin_objects.rest_deposits + '?per_page=' + this.props.perPages + '&page=' + offset;


        for (let filterIdx in filters) {
            if (null !== filters[filterIdx]) {
                url += '&' + filterIdx + '=' + filters[filterIdx];
            }
        }

        for (let sortingElt in sorting) {
            url += '&' + sortingElt + '=' + sorting[sortingElt];
        }

        this.setState({ "isloading": true });

        // let matList = document.querySelector(".deposits-list");
        // window.getComputedStyle(matList).height;
        // matList.style.maxHeight= window.getComputedStyle(matList).height;

        fetch(url)
            .then(response => response.json())
            .then(myJSON => {
                let objLength = myJSON.length;
                let newState = this.state;
                newState.locations = [];
                myJSON.forEach((elt, idx) => {
                    newState.deposits[newState.loadedIdx + idx] = this.formatDeposit(elt);
                    newState.locations.push(elt["location"]);
                    newState.deposit_types.push(elt["deposit_type"]);
                });
                newState.deposits = newState.deposits.filter((el, idx) => { return el.hasOwnProperty("id") && idx < (newState.loadedIdx + objLength) })

                newState.loadedIdx += objLength;
                newState.offset++;
                newState.isloading = false;
                newState.canLoadMore = true;
                if (this.props.perPages > objLength) {
                    newState.canLoadMore = false;
                }
                this.setState(newState);
                eventBus.dispatch('depositsUpdate', newState.deposits );
                //document.getElementById('deposits-loader').classList.remove("show");
            });
    }

    fetchDepositsTypes(pageFetched = 1) {
        let url = admin_objects.rest_url + 'deposit_type?per_page=100';

        if ( pageFetched > 1 ){
            url = url + '&page=' + pageFetched;
        }

        fetch(url)
            .then(response => {
                let totalPages = parseInt(response.headers.get("x-wp-totalpages"))
                if ( totalPages > pageFetched) {
                    this.fetchDepositsTypes(++pageFetched);
                }
                return response.json();
            })
            .then(myJSON => {

                let depositsTypes = this.state.deposit_types;
                if (1 === pageFetched) {
                    depositsTypes = [];
                }
                myJSON.forEach(
                    function (elt) {
                        depositsTypes.push({ "label": elt.name, "value": elt.id });
                    }
                )
                this.setState({ 'deposit_types': depositsTypes });
            }
            );
    }

    hideLoadMore() {
        document.querySelector(".load-more").classList.add("hidden");
    }

    showLoadMore() {
        document.querySelector(".load-more").classList.remove("hidden");
    }

    loadMore() {
        this.fetchDeposits();
    }


    formatDeposit(rawProduct) {

        let color = '';
        if (rawProduct['deposit_type_meta'][rawProduct['deposit_type']]) {
            color = rawProduct['deposit_type_meta'][rawProduct['deposit_type']]['color'][0]
        }
        let depositType = '';

        if (0 < rawProduct['deposit_type'].length) {
            let depositTypeIdx = this.state.deposit_types.findIndex((elt) => { return (parseInt(elt.value) === parseInt(rawProduct['deposit_type'][0])); });
            if (-1 < depositTypeIdx) {
                depositType = this.state.deposit_types[depositTypeIdx].label;
            }
        }


        return {
            "id": rawProduct["id"],
            "title": rawProduct["title"]["rendered"],
            "availability": rawProduct['post-meta-fields']["availability_details"],
            "featured_img_id": rawProduct["featured_media"],
            'location': rawProduct["location"],
            'city': rawProduct["city"],
            'iris': rawProduct["iris"],
            "link": rawProduct['link'],
            "qty": rawProduct['materials'],
            "type": depositType,
            'color': color,
        };
    }



    render() {
        let loaderClass = ["loading-wrapper", "hidden"];
        let loadMoreClass = ["load-more-wrapper"];
        let depositList = "deposits-items";
        if (!this.state.canLoadMore) { loadMoreClass.push("hidden"); }
        if (this.state.isloading) {
            loaderClass.pop();
            if (!loadMoreClass.includes("hidden")) { loadMoreClass.push("hidden"); }
            depositList += " loading";
        }
        let loaderClassStr = loaderClass.join(" ");
        let loadMoreClassStr = loadMoreClass.join(" ");

        return (
            <>
                <div className={depositList}>
                    {this.state.deposits && this.state.deposits.map((deposit, index) => (
                        <Deposit key={index} details={deposit} />
                    ))}
                </div>
                <div className={loaderClassStr}>
                    <svg id="refair-favicon" data-name="refair_favicon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 136.55 184.03">
                        <path className="loading-image" d="M126.31,24.65C104.61-6.1,56.92-8.48,32.26,19.77c-21.83,25-23.34,60.82-13.81,91.54l-4.12,0c-19.08.25-19.14,29.93,0,29.68,5.86-.07,11.82-.21,17.82-.5C49.46,166.48,77.66,183.07,110,184c19.11.56,19.09-29.12,0-29.68A64.48,64.48,0,0,1,67,136.27c16-3.56,31.13-9.8,43.76-20.93C135.19,93.79,146.11,52.71,126.31,24.65ZM94.91,89.31c-11.51,13-28.35,18.16-45.74,20.34-.39-1-.8-2-1.15-3C39.53,82.87,37.73,34.11,72.21,29.62,108.9,24.84,114.49,67.28,94.91,89.31Z" />
                    </svg>
                    <p>Chargement</p>
                </div>
                <div className={loadMoreClassStr}><a className="load-more" onClick={this.loadMore}>Charger +</a></div>
            </>);
    }
}

class Deposit extends React.Component {

    constructor(props) {
        super(props);
        this.state = { "ContentLoaded": "loading", "ImgLoaded": "loading" };
        this.onLoaded = this.onLoaded.bind(this);
    }

    onLoaded(statut) {
        let loaded = "";
        statut ? loaded = "" : loaded = "loading";
        this.setState((state) => { return { "ImgLoaded": loaded } });
    }

    render() {
        const { details } = this.props;

        let deposit_link = details['link'] ? details['link'] : "#";
        let deposit_loc = details['location'] ? details['location'] : "-";


        const regex = /^([0-9]{4})-([0-9]{2})-[0-9]{2}$/;
        let av_str = (<span>{details.availability}</span>);
        let m = null;
        if ((m = regex.exec(details.availability)) !== null) {
            let qNb = Math.ceil(Number.parseInt(m[2]) / 3);
            av_str = (<span>{"Dispo"}<br />{"Trim " + qNb}<br />{m[1]}</span>);
        }

        let color = 'green';
        if ('' !== details.color) { color = details.color; }
        let cardClasses = "deposit-card card-" + color;

        return (<>
            <article className={cardClasses}>
                <div className="top-part">
                    {details.type}
                </div>
                <div className="featured-img">
                    <a href={details.link}><DepositImg id={details.featured_img_id} /></a>
                </div>
                <h3 className="designation">{details.title}</h3>
                <div className="lower-part">
                    <div className="left-side">
                        <div className="deposit"><span>{deposit_loc}</span></div>
                        <div className="qty">
                            <span>{details.qty} éléments d'inventaire</span>
                        </div>
                    </div>
                    <div className="right-side">
                        <div className="availability">{av_str}</div>
                    </div>
                </div>
                <div className="footer">
                    <div className="see-more"><a href={details.link}><i className="icono-arrow-left" ></i></a></div>
                </div>
            </article>
        </>);
    }
}

class DepositImg extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            "src": '#',
            "srcset": '',
            'sizes': '',
            "alt": ''
        }

        let src = "#";
        if (props.id == "0") { src = admin_objects.default_thumbnail; }
        if (src == "#") {
            this.fetchFeaturedMedia(props.id);
        }
    }

    fetchFeaturedMedia(id) {

        let url = admin_objects.rest_url + `media/${id}`;

        fetch(url)
            .then(response => response.json())
            .then(myJSON => {

                let newState = this.state;
                newState.src = myJSON.source_url;
                newState.alt = myJSON.alt_text;

                let minHeigth = 300
                let minWidth = Math.ceil(minHeigth * parseInt(myJSON.media_details.width) / parseInt(myJSON.media_details.height));
                newState.srcset = Object.values(myJSON.media_details.sizes).reduce((srcset, sizeData, idx, sizesData) => { return srcset + (0 !== idx ? ',' : '') + sizeData.source_url + ' ' + sizeData.width + 'w' }, '');
                newState.sizes = `(min-width: 768px) ${minWidth}px,100vw`;

                this.setState(newState);
            });

    }


    render() {
        let defaultClass = "";
        if (this.state.src == "#") { defaultClass = "default"; }
        return (<img className={defaultClass} src={this.state.src} srcSet={this.state.srcset} sizes={this.state.sizes} alt={this.state.alt} />);
    }
}


class Filters extends React.Component {

    constructor(props) {
        super(props);

        this.state = { 'locationsFetched': false, 'providersFetched': false, 'currentProvider': '0', 'locations': [], 'providers': [], 'offset': 0, 'filterCollapse': true, 'currentLocation': null };
        this.filterDeposits = this.filterDeposits.bind(this);
        this.toFixFilterBox = this.toFixFilterBox.bind(this);
        this.setFilterBoxBehaviours = this.setFilterBoxBehaviours.bind(this);
        this.handleKeyDown = this.handleKeyDown.bind(this);
        this.handleLocationChange = this.handleLocationChange.bind(this);
        this.handleProviderChange = this.handleProviderChange.bind(this);
        this.toggleFilters = this.toggleFilters.bind(this);
        this.externalLocationFilterSet = this.externalLocationFilterSet.bind(this);
        this.toFixAside = this.toFixAside.bind(this);

        window.addEventListener('resize', this.setFilterBoxBehaviours);
        this.setFilterBoxBehaviours();
    }

    componentDidMount() {
        let filtersBlock = document.querySelector(".deposits-filters");
        this.setState({ "offset": filtersBlock.offsetTop });
        window.addEventListener("keydown", this.handleKeyDown);
        eventBus.on("depositHighLight", this.externalLocationFilterSet);
        this.fetchLocations();
        this.fetchProviders();

    }

    setComponentReady() {
        if (this.state.locationsFetched === true && this.state.providersFetched === true) {
            this.filterDeposits();
            eventBus.dispatch('filteringReady');
        }
    }

    fetchLocations() {
        let url = admin_objects.rest_locations;

        fetch(url)
            .then(response => response.json())
            .then(myJSON => {

                let jsonLocations = Object.values(myJSON).map((elt) => { return { 'label': elt.name, 'value': elt.term_taxonomy_id }; });
                let localFetched = this.state.locationsFetched;
                this.setState({ 'locations': jsonLocations, 'locationsFetched': true });
                if (false === localFetched) {
                    this.setComponentReady();
                }


            });
    }

    fetchProviders() {
        let url = admin_objects.rest_url + 'deposit_type/?per_page=99';

        fetch(url)
            .then(response => response.json())
            .then(myJSON => {
                let jsonProviders = myJSON.map((elt) => { return { 'label': elt.name, 'value': elt.id }; });
                let providerReference = this.getProviderReference()
                let provider_arr = jsonProviders.filter((elt) => providerReference == elt.value);
                let provider = null;
                if (provider_arr.length > 0) {
                    provider = provider_arr[0];
                }
                let localFetched = this.state.providersFetched;
                this.setState({ 'providers': jsonProviders, 'currentProvider': provider, 'providersFetched': true });
                if (false === localFetched) {
                    ;
                    this.setComponentReady();
                }
            });
    }


    /**
     *  to handle scroll event on filter box according to viewport width
     */
    setFilterBoxBehaviours() {
        if (window.innerWidth > 991) {
            // document.addEventListener('scroll', this.toFixFilterBox);
            document.addEventListener('scroll', this.toFixAside);
            // this.toFixFilterBox();
            this.toFixAside()
        } else {
            // document.removeEventListener('scroll', this.toFixFilterBox);
            document.removeEventListener('scroll', this.toFixAside);
        }
    }

    /**
     *  set filter box fixed according to scroll position
     */
    toFixFilterBox() {
        let filtersBlock = document.querySelector(".deposits-filters");
        let filterBlockStyle = getComputedStyle(filtersBlock)
        let mainMenu = document.querySelector(".site-header");
        let wrappingSection = document.querySelector(".section-body .deposits-list");
        let sideBar = document.querySelector(".aside-deposits");
        if ((window.pageYOffset + mainMenu.offsetTop + mainMenu.offsetHeight) >= wrappingSection.offsetTop) {
            filtersBlock.style.position = "fixed";
            filtersBlock.style.top = (mainMenu.offsetTop + mainMenu.offsetHeight + 20).toString() + "px";
            filtersBlock.style.width = (sideBar.offsetWidth - parseInt(filterBlockStyle.marginRight) - parseInt(filterBlockStyle.marginLeft)).toString() + "px";
        } else {
            filtersBlock.style.position = "relative";
            filtersBlock.style.top = 0;
            filtersBlock.style.width = "auto";
        }
    }

    toFixAside() {

        let filtersBlock = document.querySelector(".aside-deposits-inner");
        filtersBlock.style.zIndex = 0;
        let filterBlockStyle = getComputedStyle(filtersBlock)
        let mainMenu = document.querySelector(".site-header");
        let wrappingSection = document.querySelector(".section-body .deposits-list,.section-body .deposits");
        let sideBar = document.querySelector(".aside-deposits");
        if (sideBar.offsetHeight > filtersBlock.offsetHeight) {
            if (((window.pageYOffset + mainMenu.offsetTop + mainMenu.offsetHeight) >= wrappingSection.offsetTop)
                && ((sideBar.offsetTop + sideBar.offsetHeight - window.pageYOffset - mainMenu.offsetTop - mainMenu.offsetHeight - 20) > filtersBlock.offsetHeight)) {
                filtersBlock.style.position = "fixed";
                filtersBlock.style.top = (mainMenu.offsetTop + mainMenu.offsetHeight + 20).toString() + "px";
                filtersBlock.style.bottom = null;
                filtersBlock.style.width = (sideBar.offsetWidth - parseInt(filterBlockStyle.marginRight) - parseInt(filterBlockStyle.marginLeft)).toString() + "px";
            } else {

                // filtersBlock.style.width = "100%";
                if ((sideBar.offsetTop + sideBar.offsetHeight - window.pageYOffset - mainMenu.offsetTop - mainMenu.offsetHeight - 20) <= filtersBlock.offsetHeight) {
                    filtersBlock.style.position = 'absolute';
                    filtersBlock.style.bottom = 0;
                    filtersBlock.style.top = null;
                } else {
                    filtersBlock.style.position = "relative";
                    filtersBlock.style.top = 0;
                    filtersBlock.style.bottom = null;
                }
            }
        } else {
            filtersBlock.style.position = "relative";
            filtersBlock.style.top = 0;
            filtersBlock.style.bottom = null;
        }
    }



    handleKeyDown(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            document.getElementById("deposits-filters-validation").click();
            return false;
        }
    }

    handleLocationChange(e) {

        let newValue = null;
        if (e && e.value) {
            newValue = e.value;
        }
        this.setState({ currentLocation: e });
    }

    handleProviderChange(e) {

        let newValue = null;
        if (e && e.value) {
            newValue = e.value;
        }
        this.setState({ currentProvider: e });
    }

    externalLocationFilterSet(id) {
        let newLocation = null;
        let selectedOption = this.state.locations.filter(elt => elt.value == id);
        if (0 === selectedOption.length) { selectedOption = this.state.locations.filter(elt => elt.label == id); }
        if (0 !== selectedOption.length) { newLocation = { 'label': selectedOption[0].label, 'value': selectedOption[0].value }}
        this.setState({ 'currentLocation': newLocation });
        this.filterDeposits();
    }

    filterDeposits() {

        let filters = { "date_from": "", "date_to": "" };
        let form = document.querySelector("form.filters-form");

        if (null !== this.state.currentLocation) {
            filters["city"] = this.state.currentLocation.value;
        }

        if (null !== this.state.currentProvider) {
            filters["deposit_type"] = this.state.currentProvider.value;
        }

        filters["date_from"] = form.querySelector("input[name=deposits-availabality-from]").value;
        filters["date_to"] = form.querySelector("input[name=deposits-availabality-to]").value;
        filters["search"] = form.querySelector("input[name=deposits-search]").value;

        eventBus.dispatch("updateFilters", filters);
    }

    getProviderReference() {
        let parentReference = "0";
        let parentReferenceNode = document.querySelector("article.provider");
        if (null !== parentReferenceNode) {
            parentReference = parentReferenceNode.getAttribute('data-provider-id');
        }
        return parentReference;
    }

    toggleFilters() {
        var panel = document.querySelector(".deposits-filters");
        panel.classList.toggle('collapsed');
        var formPanel = document.querySelector(".deposits-filters form");
        if (formPanel.style.maxHeight != "0px" && formPanel.style.maxHeight != "") {
            formPanel.style.maxHeight = 0;
        } else {
            formPanel.style.maxHeight = formPanel.scrollHeight + "px";
        }

        this.setState({ filterCollapse: !this.state.filterCollapse });
    }

    render() {

        let optionsLocations = this.state.locations.map(function (elt) { return { "value": elt.value, "label": elt.label } });
        let optionsProviders = this.state.providers.map(function (elt) { return { "value": elt.value, "label": elt.label } });
        let filterCollapseClass = this.state.filterCollapse ? "collapse-action closed" : "collapse-action";

        let currentLocationValue = null;
        if ((null !== this.state.currentLocation) && ('0' !== this.state.currentProvider)) { currentLocationValue = this.state.currentLocation; }
        let currentProviderValue = null;
        if ((null !== this.state.currentProvider) && ('0' !== this.state.currentProvider)) { currentProviderValue = this.state.currentProvider; }

        return (
            <>
                <div className="title" onClick={this.toggleFilters}>Filtres<span className={filterCollapseClass}>&gt;</span></div>
                <form className="filters-form" >
                    <div className="form-inner">
                        <div className="filter-block">
                            <label className="deposits-location-label control-title" htmlFor="deposits-location-select">Localisation</label>
                            <Select onChange={this.handleLocationChange} options={optionsLocations} placeholder="Sélectionner..." isClearable value={currentLocationValue} />
                        </div>
                        <div className="filter-block">
                            <label className="deposits-providers-label control-title" htmlFor="deposits-provider-select">Fournisseur</label>
                            <Select onChange={this.handleProviderChange} options={optionsProviders} placeholder="Sélectionner..." isClearable value={currentProviderValue} />
                        </div>
                        <div className="filter-block">
                            <label className="deposits-availabality-label control-title" hmtlfor="deposits-availabality-from">Dates de disponibilités</label>
                            <div className="filter-date"><span>Du</span><input type="date" name="deposits-availabality-from" id="deposits-availabality-from" /></div>
                            <div className="filter-date"><span>au</span><input type="date" name="deposits-availabality-to" id="deposits-availabality-to" /></div>
                        </div>
                        <div className="filter-block grid-70">
                            <span className="control-title" >Disponibilité</span>
                            <input type="checkbox" name="deposits-in-stock" id="deposits-in-stock" /><label hmtlfor="deposits-in-stock">En stock</label>
                        </div>
                        <div className="filter-block">
                            <label className="deposits-search-label control-title" hmtlfor="deposits-search">Mots clés</label>
                            <input type="text" name="deposits-search" id="deposits-search" />
                        </div>
                        <div className="filter-validation"><a id="deposits-filters-validation" className="filter-validation-btn" onClick={this.filterDeposits}>Filtrer</a></div>
                    </div>
                </form>
            </>);
    }
}

class Sorting extends React.Component {

    static get sortingTypes() { return { 'prop-title': "Désignation", 'meta-location': "Localisation", 'meta-dismantle_date': "Date" }; } //
    static get sortingOrder() { return { 'asc': "croissante", 'desc': "décroissante" }; }

    constructor(props) {
        super(props);
        this.state = { sortingCollapse: true, sort: 'prop-title', order: 'asc' };
        //window.addEventListener('resize',this.setSortingBoxBehaviours);
        //this.setSortingBoxBehaviours();

        //this.toFixSortingBox = this.toFixSortingBox.bind(this);
        this.setSortingBoxBehaviours = this.setSortingBoxBehaviours.bind(this);
        this.toggleSorting = this.toggleSorting.bind(this);
        this.handleSortingChange = this.handleSortingChange.bind(this);
        this.handleOrderingChange = this.handleOrderingChange.bind(this);
        this.dispatchUpdateSorting = this.dispatchUpdateSorting.bind(this);
    }

    componentDidMount() {
        eventBus.dispatch('sortingReady');
        eventBus.dispatch("updateSorting", { sort: this.state.sort, order: this.state.order });
    }

    handleSortingChange(e) {
        this.setState({ sort: e.target.value });
        this.dispatchUpdateSorting(e.target.value, this.state.order);
    }

    handleOrderingChange(e) {

        this.setState({ order: e.target.value });
        this.dispatchUpdateSorting(this.state.sort, e.target.value);
    }

    dispatchUpdateSorting(sort, order) {
        eventBus.dispatch("updateSorting", { sort: sort, order: order });
    }


    /**
     *  to handle scroll event on filter box according to viewport width
     */
    setSortingBoxBehaviours() {
        if (window.innerWidth > 991) {
            document.addEventListener('scroll', this.toFixSortingBox);
            this.toFixSortingBox();
        } else {
            document.removeEventListener('scroll', this.toFixSortingBox);
        }
    }

    /**
     *  set filter box fixed according to scroll position
     */
    toFixSortingBox() {
        let sortingBlock = document.querySelector(".deposits-sorting");
        let sortingBlockStyle = getComputedStyle(sortingBlock)
        let mainMenu = document.querySelector(".site-header");
        let wrappingSection = document.querySelector(".section-body .deposits-list");
        let sideBar = document.querySelector(".aside-deposits");
        if ((window.pageYOffset + mainMenu.offsetTop + mainMenu.offsetHeight) >= wrappingSection.offsetTop) {
            sortingBlock.style.position = "fixed";
            sortingBlock.style.top = (mainMenu.offsetTop + mainMenu.offsetHeight + 20).toString() + "px";
            sortingBlock.style.width = (sideBar.offsetWidth - parseInt(sortingBlockStyle.marginRight) - parseInt(sortingBlockStyle.marginLeft)).toString() + "px";
        } else {
            sortingBlock.style.position = "relative";
            sortingBlock.style.top = 0;
            sortingBlock.style.width = "100%";
        }
    }

    toggleSorting() {
        var panel = document.querySelector(".deposits-sorting");
        panel.classList.toggle('collapsed');
        var formPanel = document.querySelector(".deposits-sorting .sorting-form");
        if (formPanel.style.maxHeight != "0px" && formPanel.style.maxHeight != "") {
            formPanel.style.maxHeight = 0;
        } else {
            formPanel.style.maxHeight = formPanel.scrollHeight + "px";
        }
        this.setState({ sortingCollapse: !this.state.sortingCollapse });
    }


    render() {

        let sortingCollapseClass = this.state.sortingCollapse ? "collapse-action closed" : "collapse-action";
        let sortingTypes = Sorting.sortingTypes;
        let sortingOrder = Sorting.sortingOrder;

        let options = Object.entries(sortingTypes).reduce(function (accType, type) {
            let value = `${type[0]}`;
            let text = `${type[1]}`;
            return (<>{accType}<option value={value}>{text}</option></>);
        }, (<></>))

        let sortOrderAscClass = 'sort-order';
        let sortOrderDescClass = 'sort-order';

        switch (this.state.order) {
            case 'desc':
                sortOrderDescClass += ' active';
                break;
            case 'asc':
            default:
                sortOrderAscClass += ' active';
                break;
        }

        return (<>
            <div className="title" onClick={this.toggleSorting}>Tri <span className={sortingCollapseClass}>&gt;</span></div>
            <form className="sorting-form" >
                <div className="form-inner" >
                    <select onChange={this.handleSortingChange}>
                        {options}
                    </select>
                    <div className="sort-order-wrapper asc"><label htmlFor="sort-order-asc" className={sortOrderAscClass}>&gt;</label><input id='sort-order-asc' type="radio" name='order' onChange={this.handleOrderingChange} value='asc' checked={this.state.order === 'asc'} /></div>
                    <div className="sort-order-wrapper desc"><label htmlFor="sort-order-desc" className={sortOrderDescClass}>&gt;</label><input id='sort-order-desc' type="radio" name='order' onChange={this.handleOrderingChange} value='desc' checked={this.state.order === 'desc'} /></div>
                </div>
            </form>
        </>)
    }
}



window.addEventListener('load', () => {
    // Render the app inside our shortcode's #app div
    if (document.getElementById('deposits-display') != null) {
        let perPages = 9;
        ReactDom.render(
            <Deposits perPages={perPages} />,
            document.getElementById('deposits-display'));

    }
    if (document.getElementById('deposits-filters') != null) {
        ReactDom.render(
            <Filters />,
            document.getElementById('deposits-filters'));
    }
    if (document.getElementById('deposits-sorting') != null) {
        ReactDom.render(
            <Sorting />,
            document.getElementById('deposits-sorting'));
    }

});
