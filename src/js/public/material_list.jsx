'use strict';
import React from 'react';
import ReactDom from 'react-dom';
import { ReactNotifications, Store } from 'react-notifications-component';
import parse from 'html-react-parser';
import 'react-notifications-component/dist/theme.css';
import Select from 'react-select';

const eventBus = {
    on(event, callback) {
      document.addEventListener(event, (e) => callback(e.detail));
    },
    dispatch(event, data) {
      document.dispatchEvent(new CustomEvent(event, { detail: data }));
    },
    remove(event, callback) {
      document.removeEventListener(event, callback);
    },
  };

  Array.prototype.equals = function(arr2) {
    return (
      this.length === arr2.length &&
      this.every((value, index) => value === arr2[index])
    );
  };

class Materials extends React.Component {

    constructor(props) {
        super(props);
        let deposit = this.getDepositReference();
        let deposit_type = this.getDeposit_typeReference();
        this.state = {"materials":[],"offset":0,"loadedIdx":0,"deposit":deposit,'deposit_type':deposit_type,"isloading":false,"canLoadMore":true,filters:{},"order":'asc',"orderby":"title","ordertype":"prop"}

        this.fetchMaterials = this.fetchMaterials.bind(this);
        this.loadMore = this.loadMore.bind(this);
        this.onUpdateFilters = this.onUpdateFilters.bind(this);
        this.onUpdateSorting = this.onUpdateSorting.bind(this);
    }

    componentDidMount(){
        eventBus.on("updateFilters", this.onUpdateFilters);
        eventBus.on("updateSorting", this.onUpdateSorting);
        //this.fetchMaterials();
    }

    componentDidUpdate(){
        if (!this.state.isloading){
            let matList = document.querySelector(".materials-list");
            let matCard = document.querySelector(".materials-list .material-card:not(.hidden)");
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

    onUpdateFilters(newFilters){

        this.resetMaterials();
        
        this.setState({filters:newFilters});
        let sorting = {"order":this.state.order, "orderby": this.state.orderby, "ordertype":this.state.ordertype}
        this.fetchMaterials(newFilters,sorting);
    }

    onUpdateSorting(rawSorting){
        let {order,orderby,ordertype} = this.state;

        let sortings = rawSorting['sort'].split("-");
        ordertype = sortings[0];
        orderby = sortings[1];
        order = rawSorting['order'];

        let sorting = {"ordertype":ordertype,"orderby":orderby,"order":order};
        let newState = this.state;
        newState.ordertype = ordertype;
        newState.orderby = orderby;
        newState.order = order;
        this.setState(newState);

        this.resetMaterials();
        this.fetchMaterials(this.state.filters, sorting);

    }

    addToCart(data){

        let url = admin_objects.ajax_url;
   
        fetch(url, {
          method: "POST",
          credentials: 'same-origin',
          body: data
        })
        .then((response) => response.json())
        .then((data) => {

            if ( data.error ){

                eventBus.dispatch("displayNotification", {
                    title: "Ajout de matériaux à votre liste!",
                    message: "Une erreur est survenue à l'ajout de martériau à votre liste",
                    type: "error",
                    insert: "top",
                    container: "top-center",
                    animationIn: ["animate__animated", "animate__zoomIn"],
                    dismiss: {
                      duration: 7000
                    }
                  });

            }else{
                console.log(data.fragments);
                let fragments = Object.values( data.fragments ).reduce((acc, elt ) => acc + elt,"");
    
                eventBus.dispatch("displayNotification",{
                    title: "Ajout de matériaux à votre liste",
                    content: parse(fragments),
                    type: "custom",
                    insert: "top",
                    container: "top-center",
                    animationIn: ["animate__animated", "animate__zoomIn"],
                    dismiss: {
                        duration: 7000
                      }
                  });
            }

        })
        .catch((error) => {
          console.log('[REFAIR]');
          console.error(error);
        });
    }

    resetMaterials(){
        let newState = this.state;
        newState.offset = 0;
        newState.loadedIdx = 0;
        newState.materials = [];
        this.setState(newState);
    }

    fetchMaterials(filters,sorting){

        filters = (filters)? filters : this.state.filters;
        sorting = (sorting) ? sorting : {"order":this.state.order,"orderby":this.state.orderby,"ordertype":this.state.ordertype};
        if (this.state.deposit !="0" ){filters['product_deposit']=[this.state.deposit]}
        if (this.state.deposit_type !="0" ){filters['product_deposit_type']=[this.state.deposit_type]}
        let offset = this.state.offset;
        //document.getElementById('materials-loader').classList.add("show");
        let url=admin_objects.rest_materials +'?per_page='+this.props.perPages+'&offset='+offset;


        for (let filterIdx in filters){
            if (null !== filters[filterIdx]){
                url+='&'+filterIdx+'=' + filters[filterIdx];
            }
        }

        for(let sortingElt in sorting){
            url+='&'+sortingElt+'=' + sorting[sortingElt];
        }

        this.setState({"isloading":true});

        // let matList = document.querySelector(".materials-list");
        // window.getComputedStyle(matList).height;
        // matList.style.maxHeight= window.getComputedStyle(matList).height;

        fetch( url)
        .then(response => response.json())
        .then(myJSON => {
            let objLength = myJSON["materials"].length;
            let newState = this.state;
            myJSON["materials"].forEach((elt, idx)=>{
                newState.materials[newState.loadedIdx+idx] = this.formatMaterial(elt);
            });
            newState.materials = newState.materials.filter((el,idx)=>
                {return el.hasOwnProperty("id") && idx < (newState.loadedIdx + objLength)})

            newState.loadedIdx += objLength;
            newState.offset=myJSON["offset"];
            newState.isloading=false;
            newState.canLoadMore=true;
            if (this.props.perPages>objLength){
                newState.canLoadMore=false;
            }
            this.setState(newState);
            //document.getElementById('materials-loader').classList.remove("show");
        });
    }

    hideLoadMore(){
        document.querySelector(".load-more").classList.add("hidden");
    }

    showLoadMore(){
        document.querySelector(".load-more").classList.remove("hidden");
    }

    loadMore(){
        this.fetchMaterials();
    }


    formatMaterial(rawProduct){

        return {            
            "id": rawProduct["ID"],
            "title": rawProduct["post_title"],
            "family": rawProduct["taxonomy"]['family'],
            "category": rawProduct["taxonomy"]['category'],
            "deposit": {"name":rawProduct["deposit"]["location"],"link":rawProduct["deposit"]["link"]},
            "dimensions": rawProduct['woocommerce']["dimensions"] ? rawProduct['woocommerce']["dimensions"]: "ND",
            "conditions": rawProduct['woocommerce']["conditions"]? rawProduct['woocommerce']["conditions"]: "ND",
            "qty": rawProduct['woocommerce']["stock_qty"],
            'sku': rawProduct['woocommerce']['sku'],
            'unit': rawProduct['woocommerce']['unit'],
            "availability": rawProduct['deposit']["availability"],       
            "featured_img":  rawProduct['woocommerce']["featured_img"]?{"url":rawProduct['woocommerce']["featured_img"],"alt":rawProduct["post_title"]} : {"url":"#","alt":""},
            "link": rawProduct['woocommerce']["link"],
            "isVariable": rawProduct['woocommerce'].hasOwnProperty("variations") &&  Array.isArray(rawProduct['woocommerce']["variations"]) &&rawProduct['woocommerce']["variations"].length >0,
            "add_to_cart_link": rawProduct['woocommerce']["add_to_cart_link"]
        };
    }

    getDepositReference(){
        let returned="0";
        let parentReference = "0";
        let parentReferenceNode = document.querySelector("article.deposit");
        if (parentReferenceNode){
            parentReference = parentReferenceNode.attributes['data-id'].value;
        }
        if (parentReference){ returned = parentReference;}
        return returned;
    }

    getDeposit_typeReference() {
        let returned = "0";
        let parentReference = "0";
        let parentReferenceNode = document.querySelector("article.deposit_type");
        if (parentReferenceNode) {
            parentReference = parentReferenceNode.attributes['data-deposit_type-id'].value;
        }
        if (parentReference) { returned = parentReference; }
        return returned;
    }
  

    render(){
        let loaderClass=["loading-wrapper", "hidden"];
        let loadMoreClass=["load-more-wrapper"];
        let materialList = "materials-list";
        if (!this.state.canLoadMore){loadMoreClass.push("hidden"); }
        if(this.state.isloading){
            loaderClass.pop();
            if (!loadMoreClass.includes("hidden")){ loadMoreClass.push("hidden");}
            materialList += " loading";
        }
        let loaderClassStr = loaderClass.join(" ");
        let loadMoreClassStr = loadMoreClass.join(" ");

        return (
            <>            
            <div className={materialList}>
                {this.state.materials && this.state.materials.map((material, index) =>(
                    <Material key={index} details={material} onAddToCart={this.addToCart}/>
                ))}
            </div>
            <div className={loaderClassStr}>
                <svg id="refair-favicon" data-name="refair_favicon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 136.55 184.03">
                    <path className="loading-image" d="M126.31,24.65C104.61-6.1,56.92-8.48,32.26,19.77c-21.83,25-23.34,60.82-13.81,91.54l-4.12,0c-19.08.25-19.14,29.93,0,29.68,5.86-.07,11.82-.21,17.82-.5C49.46,166.48,77.66,183.07,110,184c19.11.56,19.09-29.12,0-29.68A64.48,64.48,0,0,1,67,136.27c16-3.56,31.13-9.8,43.76-20.93C135.19,93.79,146.11,52.71,126.31,24.65ZM94.91,89.31c-11.51,13-28.35,18.16-45.74,20.34-.39-1-.8-2-1.15-3C39.53,82.87,37.73,34.11,72.21,29.62,108.9,24.84,114.49,67.28,94.91,89.31Z"/>
                </svg>
                <p>chargement</p>
            </div>
            <div className={loadMoreClassStr}><a className="load-more" onClick={this.loadMore}>Charger +</a></div>
            </>);
    }
}

class Material extends React.Component {

    constructor(props) {
        super(props);          
        this.state = { "ContentLoaded": "loading", "ImgLoaded": "loading"};
        if (this.props.details.qty >0){ this.state.qty=1;}
        this.onLoaded = this.onLoaded.bind(this); 
        this.onCartSubmit = this.onCartSubmit.bind(this); 
        this.onChangeQty = this.onChangeQty.bind(this)
    }

    onChangeQty(e){
        this.setState( { "qty":e.target.value } );
    }

    onCartSubmit(e){

        const data = new FormData();
        data.append( 'action', 'woocommerce_ajax_add_to_cart' ); 
        data.append( 'product_id', this.props.details.id );
        data.append( 'product_sku', this.props.details.sku );
        data.append( 'quantity', this.state.qty);

        this.props.onAddToCart(data);

        //window.location.href = this.props.details.add_to_cart_link + "&quantity=" + this.state.qty;

    }

    
    
    renderAddToList(){
        let addToListLabel = "Choisir une déclinaison";
        let addToListBlock = (<div className="add-to-cart-wrapper"><a className="add-to-cart" href={this.props.details.add_to_cart_link}>{addToListLabel}</a></div>);
        if (!this.props.details.isVariable ){
            addToListLabel = "Stock épuisé";
            addToListBlock = (<div className="add-to-cart-wrapper"><a className="add-to-cart" disabled>{addToListLabel}</a></div>);
            if (this.props.details.qty > 0 ){

                let options =[];            
                for (let index = 1; index <= this.props.details.qty; index++) {
                    options.push((<option key={index} value={index}>{index}</option>));
                }
                addToListLabel  = "Ajouter à ma liste";
                addToListBlock = (  <>
                                        <div className="select select-qty">
                                            <select value={this.state.qty} onChange={this.onChangeQty}>
                                                {options}
                                            </select>
                                        </div>
                                        <div className="add-to-cart-wrapper">
                                            <button type="submit" className="add-to-cart" onClick={this.onCartSubmit}>{addToListLabel}</button> 
                                        </div>
                                    </>)
            }            
        }    
        return addToListBlock;
    }

    onLoaded(statut){
        let loaded="";
        statut ? loaded="": loaded="loading";
        this.setState((state) =>{ return {"ImgLoaded" : loaded}});
    }

    render(){
        const {details} = this.props;

        let deposit_link = details.deposit['link']?details.deposit['link']: "#";
        let deposit_loc = details.deposit['name']?details.deposit['name']:"-";
        let dimensions = undefined;
        let conditions = undefined;
        let dimBlockClassName = "dimensions part-block case-1-1";
        if(Array.isArray(details.dimensions)){
            dimensions = details.dimensions.map(function(el,idx){
                let dimItems=el;
                let dimClassName = "dimensions-elt";
                if (Array.isArray(el)){
                    if(!dimBlockClassName.includes(" wide")){ dimBlockClassName += " wide"};
                    dimItems = el.map((subEl,idx)=>(<span key={idx}>{subEl}</span>))
                }
                return (
                <div className={dimClassName} key={idx}>
                    {dimItems}
                </div>);
            })
        }else{
            dimensions = details.dimensions;
        }
        if(Array.isArray(details.conditions) && details.conditions.length > 1){conditions = (<span>{"Conditions"}<br />{"Divers"}</span>);}else{if (conditions==""){conditions ="-"}else{conditions =details.conditions;}}
        
        const regex = /^([0-9]{4})-([0-9]{2})-[0-9]{2}$/;
        let av_str = (<span>{details.availability}</span>);
        let m=null;
        if ((m = regex.exec(details.availability)) !== null) {
            let qNb = Math.ceil(Number.parseInt(m[2]) / 3);
            av_str = (<span>{"Dispo"}<br />{"Trim "+qNb}<br />{m[1]}</span>);
        }

        let category ="";
        if (details.category && typeof details.category === 'object' && details.category.hasOwnProperty('name' )){
            category = details.category['name'];
        }

        let family ="";
        if (details.family && typeof details.family === 'object' && details.family.hasOwnProperty('name')){
            family = details.family['name'];
        }
        
        let addToCart = this.renderAddToList();

        let unit = details.unit;
        if ( unit === 'u' ){
            let qtyPlural="";
            if (parseInt(details.qty)>1){qtyPlural="s"}

            unit = `Unité${qtyPlural}`;
        }

        let cardClasses = "material-card";
        if ( 1 > details.qty){
            cardClasses += " out-of-stock";
        }


        return (<>
            <article className={cardClasses}>
                <div className="family">{family}</div>
                <h3 className="designation">{details.title}</h3>
                <div className="cat">{category}</div>        
                <div className="lower-part">
                    <div className="condition-deposit part-block case-1-1">
                        <div className="wrapper">
                            <div className="deposit"><span>{deposit_loc}</span></div>
                            <div className="condition"><span>{conditions}</span></div>
                        </div>
                    </div>
                    <div className={dimBlockClassName}>
                        <span>{dimensions}</span>
                    </div>
                    <div className="qty part-block case-1-1">
                        <div><p>{details.qty}</p><br/><p>{unit}</p></div>
                    </div>                   
                    <div className="img-part part-block case-1-1">
                        <div className="img-wrapper"><MaterialImg  src={details.featured_img.url} alt={details.title} /></div>
                    </div>                    
                    <div className="availability part-block case-1-1">{av_str}</div>
                    <div className="see-more part-block case-1-1"><div className="wrapper"><a href={details.link}><i className="icono-arrow-left" ></i></a></div></div>
                    
                    <div className="card-footer part-block case-1-3">
                        <div className="wrapper">
                        {addToCart}
                        </div>
                    </div>
                </div>

            </article>
        </>);
    }
}

// class MaterialImg extends React.Component{

//     constructor(props) {
//         super(props);
//         let src = props.src ;
//         if (src =="#"){src = this.placeholderSrc(props.src);}
//         this.state = { 
//             "src": src,
//             "alt": props.alt
//         }
//     }

//     placeholderSrc() {
//         return admin_objects.default_thumbnail;
//     }
//     render(){
//         let defaultClass ="";
//         if (this.props.src=="#"){defaultClass ="default";}
//         return (<img className={defaultClass} src={this.state.src} alt={this.state.alt}/>);
//     }
// }

function MaterialImg ({src,alt} ){

    let defaultClass = "";

    if (src =="#"){
        src = placeholderSrc(src);
        defaultClass ="default";
    }

    function placeholderSrc() {
        return admin_objects.default_thumbnail;
    }
    return (
        <img className={defaultClass} src={src} alt={alt}/>
    )
}

class CartNotifications extends React.Component {


    constructor(props){
        super(props);   
        eventBus.on("displayNotification", this.displayNotification);
    }

    displayNotification(notifArgs){
        Store.addNotification(notifArgs);
    }
    render (){
       return  (<ReactNotifications />);
    }
}

class Filters extends React.Component {

    constructor(props) {
        super(props);   

        let deposit = this.getDepositReference();
        let deposit_type = this.getDeposit_typeReference();

        this.state={
            "product_relations":[],
            "product_fam":[],
            "product_cat":[],
            "product_deposit_ref":[],
            "product_deposit_location":[],
            "product_deposit_type":[],
            "deposit":parseInt(deposit),
            "deposit_type": parseInt(deposit_type),
            'offset':0,
            'filterCollapse':true,
            'filters': {'fam':[], 'cat':[], 'deposit':[], 'deposit_type':[],'city': []},
            'currentFilters' : {'fam':null, 'cat':null, 'deposit':[parseInt(deposit)], 'deposit_type': [parseInt(deposit_type)], 'city': null},
        };
        this.filterMaterials = this.filterMaterials.bind(this);
        this.toFixFilterBox = this.toFixFilterBox.bind(this);
        this.setFilterBoxBehaviours = this.setFilterBoxBehaviours.bind(this);
        this.handleKeyDown = this.handleKeyDown.bind(this);
        this.handleCategoryChange = this.handleCategoryChange.bind(this);
        this.handleFamilyChange = this.handleFamilyChange.bind(this);
        this.handleCityChange = this.handleCityChange.bind(this);
        this.handleDepositChange = this.handleDepositChange.bind(this);
        this.handleDeposit_typeChange = this.handleDeposit_typeChange.bind(this);
        this.toggleFilters = this.toggleFilters.bind(this);
        this.filterOptions = this.filterOptions.bind(this);

        this.toFixAside = this.toFixAside.bind(this);
        
        window.addEventListener('resize',this.setFilterBoxBehaviours);
        this.setFilterBoxBehaviours();
    }

    componentDidMount(){
        let filtersBlock = document.querySelector(".materials-filters");
        this.setState({"offset": filtersBlock.offsetTop});
        // this.fetchFamilies();
        // this.fetchDeposits();
        this.fetchFilters();
        window.addEventListener("keydown", this.handleKeyDown);
    }
    /**
     *  to handle scroll event on filter box according to viewport width
     */
    setFilterBoxBehaviours(){
        if (window.innerWidth > 991){
            // document.addEventListener('scroll', this.toFixFilterBox);
            document.addEventListener('scroll', this.toFixAside);
            // this.toFixFilterBox();
            this.toFixAside()
        }else{
            // document.removeEventListener('scroll', this.toFixFilterBox);
            document.removeEventListener('scroll', this.toFixAside);
        }
    }

    /**
     *  set filter box fixed according to scroll position
     */
    toFixFilterBox(){
        let filtersBlock = document.querySelector(".materials-filters");
        let filterBlockStyle = getComputedStyle(filtersBlock)
        let mainMenu = document.querySelector(".site-header");
        let wrappingSection = document.querySelector(".section-body .materials");
        let sideBar = document.querySelector(".aside-materials");
        if ((window.pageYOffset + mainMenu.offsetTop + mainMenu.offsetHeight) >= wrappingSection.offsetTop){
            filtersBlock.style.position = "fixed";
            filtersBlock.style.top = (mainMenu.offsetTop + mainMenu.offsetHeight + 20).toString()+"px";
            filtersBlock.style.width = (sideBar.offsetWidth - parseInt(filterBlockStyle.marginRight)-parseInt(filterBlockStyle.marginLeft)).toString()+"px";
        }else{
            filtersBlock.style.position = "relative";
            filtersBlock.style.top=0;
            filtersBlock.style.width = "auto";
        }
    }

    toFixAside(){
        
        let filtersBlock = document.querySelector(".aside-materials-inner");
        filtersBlock.style.zIndex = 0;
        let filterBlockStyle = getComputedStyle(filtersBlock)
        let mainMenu = document.querySelector(".site-header");
        let wrappingSection = document.querySelector(".section-body .materials");
        let sideBar = document.querySelector(".aside-materials");
        if ( sideBar.offsetHeight > filtersBlock.offsetHeight ) {
            if ((( window.pageYOffset + mainMenu.offsetTop + mainMenu.offsetHeight ) >= wrappingSection.offsetTop )
            && ( ( sideBar.offsetTop + sideBar.offsetHeight - window.pageYOffset - mainMenu.offsetTop - mainMenu.offsetHeight - 20 ) > filtersBlock.offsetHeight ) ) {
                filtersBlock.style.position = "fixed";
                filtersBlock.style.top = (mainMenu.offsetTop + mainMenu.offsetHeight + 20).toString()+"px";
                filtersBlock.style.bottom=null;
                filtersBlock.style.width = (sideBar.offsetWidth - parseInt(filterBlockStyle.marginRight)-parseInt(filterBlockStyle.marginLeft)).toString()+"px";
            } else {
                
                filtersBlock.style.width = "100%";
                if (( sideBar.offsetTop + sideBar.offsetHeight - window.pageYOffset - mainMenu.offsetTop - mainMenu.offsetHeight - 20 ) <= filtersBlock.offsetHeight ) {
                    filtersBlock.style.position = 'absolute';
                    filtersBlock.style.bottom = 0;
                    filtersBlock.style.top = null;
                }else{
                    filtersBlock.style.position = "relative";
                    filtersBlock.style.top=0;
                    filtersBlock.style.bottom=null;
                }
            }
        } else {
            filtersBlock.style.position = "relative";
            filtersBlock.style.top=0;
            filtersBlock.style.bottom=null;
        }
    }

    handleKeyDown(e){
        if (e.key === "Enter") {
            e.preventDefault();
            document.getElementById("materials-filters-validation").click();
            return false;
        }
    }

    handleCityChange(e){

        let value = null;
        if (e.length > 0 ){value = e.map(x => x.value);}
       
        let currentFilters = this.state.currentFilters;
        currentFilters['city'] = value;
        this.setState({'currentFilters': currentFilters});
    }

    handleDepositChange(e){

        let value = null;
        if (e.length > 0 ){value = e.map(x => x.value);}

        let currentFilters = this.state.currentFilters;
        currentFilters['deposit'] = value;
        this.setState({'currentFilters': currentFilters});
    }

    handleDeposit_typeChange(e){

        let value = null;
        if (e.length > 0 ){value = e.map(x => x.value);}

        let currentFilters = this.state.currentFilters;
        currentFilters['deposit_type'] = value;
        this.setState({'currentFilters': currentFilters});
    }


    handleCategoryChange(e){

        let value = null;
        if (e.length > 0 ){value = e.map(x => x.value);}

        let currentFilters = this.state.currentFilters;
        currentFilters['cat'] = value;
        this.setState({'currentFilters': currentFilters});
    }

    handleFamilyChange(e){
        let newValue = null;
        if (e && e.value){
            newValue = e.value;
        }
        let currentFilters = this.state.currentFilters;
        currentFilters['fam'] = newValue;
        currentFilters['cat'] = null;
        this.setState({'currentFilters': currentFilters});
    }

    fetchFamilies(){

        let url = admin_objects.rest_url +'product_cat/?hide_empty=true&per_page=100';
        
        fetch(url)
        .then(response => response.json())
        .then(myJSON => {
            
            let hierachicalCats=[], hierachicalParent=[];

            // let parents = myJSON.reduce((acc,elt)=>{if(!acc.includes(elt.parent)){acc.push(elt.parent);}return acc;},[]);

            myJSON.forEach(element => {
                    if (element.parent===0){hierachicalParent.push(element);return}
                    hierachicalCats.push(element);
            });

            let sortedElts = hierachicalCats.sort((a,b)=>{
                if (a.slug < b.slug)
                return -1;
                if (a.slug > b.slug)
                    return 1;
                // a doit être égal à b
                return 0;});

            let sortedParents = hierachicalParent.sort((a,b)=>{
                if (a.slug < b.slug)
                return -1;
                if (a.slug > b.slug)
                    return 1;
                // a doit être égal à b
                return 0;});

            sortedElts.forEach(elt=>{
                let catParent = elt.parent;
                let parentIdx = sortedParents.findIndex(elt=>elt.id == catParent);

                //sortedParents.splice(parentIdx+1, 0, elt);
                sortedParents.children = sortedParents.children || [];
                sortedParents.children.push(elt.id);

            })

            let newState = this.state;           
            
            newState["filters"]['fam'] = sortedParents;
            newState["filters"]['cat'] = sortedElts;

            this.setState(newState);
            });
    }

    fetchDeposits(fetchPage=1){


        let url = admin_objects.rest_deposits + "?page="+fetchPage;
        
        fetch(url)
        .then(response => {
            if (response.headers.get('X-WP-TotalPages') > fetchPage ){
                this.fetchDeposits(++fetchPage);
            }
            return response.json()})
        .then(myJSON => {
            
            let hierachicalCats=[], hierachicalParent=[];

            // let parents = myJSON.reduce((acc,elt)=>{if(!acc.includes(elt.parent)){acc.push(elt.parent);}return acc;},[]);

            let productDepositRef = [];
            let productDepositLocation = [];
            let productDeposit_type = [];

            myJSON.forEach(element => {
                productDepositRef.push(element.title.rendered);
                productDepositLocation.push(element.city[0]);
                productDeposit_type.push(element.deposit_type[0]);
            });


            let newState = this.state;           
            


            this.setState(newState);
            });
    }

    fetchFilters(){
        let url = admin_objects.rest_materials_filters;
        
        fetch(url)
        .then(response => response.json())
        .then(myJSON => {

            console.log(myJSON);

            let newState = this.state;           
            
            newState["filters"]['fam'] = myJSON['families'];
            newState["filters"]['cat'] = myJSON['categories'];
            newState["filters"]['deposit'] = myJSON['deposits'];
            newState["filters"]['city'] = myJSON['cities'];
            newState["filters"]['deposit_type'] = myJSON['deposit_type'];
            newState["product_relations"] = myJSON['deposits'].map((elt)=> elt['relations']);

            this.setState(newState);
        });
    }

    filterMaterials(){

        let filters={"product_cat":"","product_location":"","product_deposit":"","date_from":"","date_to":""};
        let form = document.querySelector("form.filters-form");
        if ( null !== this.state.currentFilters['fam'] ){
            if ( null !== this.state.currentFilters['cat'] ){
                filters["product_cat"] = this.state.currentFilters['cat'].join();
            }else{
                filters["product_cat"]  = [this.state.currentFilters['fam']];                
            }
        }
        filters["product_location"]    = this.state.currentFilters['city'];
        filters["product_deposit"]    = this.state.currentFilters['deposit'] != 0 ? this.state.currentFilters['deposit'] : '';
        filters["product_deposit_type"]    = this.state.currentFilters['deposit_type'] != 0? this.state.currentFilters['deposit_type'] : '';
        filters["date_from"]    = form.querySelector("input[name=materials-availabality-from]").value;
        filters["date_to"]      = form.querySelector("input[name=materials-availabality-to]").value;
        filters["only_in_stock"]      = form.querySelector("input[name=materials-in-stock]").checked;
        filters["search"]      = form.querySelector("input[name=materials-search]").value;
        
        eventBus.dispatch("updateFilters", filters);
    }

    getDepositReference(){
        let returned="0";
        let parentReference = "0";
        let parentReferenceNode = document.querySelector("article.deposit");
        if (parentReferenceNode){
            parentReference = parentReferenceNode.attributes['data-id'].value;
        }
        if (parentReference){ returned = parentReference;}
        return returned;
    }

    getDeposit_typeReference() {
        let returned = "0";
        let parentReference = "0";
        let parentReferenceNode = document.querySelector("article.deposit_type");
        if (parentReferenceNode) {
            parentReference = parentReferenceNode.attributes['data-deposit_type-id'].value;
        }
        if (parentReference) { returned = parentReference; }
        return returned;
    }

    toggleFilters(){
        let panel = document.querySelector(".materials-filters");
        panel.classList.toggle('collapsed');
        let formPanel = document.querySelector(".materials-filters form");
        let asidesPanels = document.querySelectorAll(".aside-materials-inner .title");

        let panelsTitlesHeight = Array.from(asidesPanels).reduce((acc, panelTitle) => acc + parseInt(panelTitle.offsetHeight),0);

        let extendedSize = formPanel.scrollHeight;
        if (extendedSize > (parseInt(window.innerHeight) - panelsTitlesHeight)){extendedSize = parseInt(window.innerHeight) - panelsTitlesHeight};

        if (formPanel.style.maxHeight !="0px" && formPanel.style.maxHeight != "") {
            formPanel.style.maxHeight = 0;
        } else {
            formPanel.style.maxHeight = extendedSize + "px";
        }

        this.setState({filterCollapse:!this.state.filterCollapse});
    }

    filterOptions(toFilter){

        let selectedRelations = this.state.product_relations.filter((relations) =>{
            return ! Object.keys(this.state.currentFilters).some(currentFilterType => {
                if ( ! relations[currentFilterType] ){ return false; }
                if ( null === this.state.currentFilters[currentFilterType] || (1 === this.state.currentFilters[currentFilterType].length && 0 === this.state.currentFilters[currentFilterType][0]) ){ return false; }
                if ( currentFilterType === toFilter) {return false;}
                if ( this.state.currentFilters[currentFilterType].some((cf) => relations[currentFilterType].includes(cf))) { return false;}
                return true;
            });
        });

        let selectedToFilterRelation = selectedRelations.flat().reduce( (acc,relation) =>{
            relation[toFilter].forEach(elt =>
                {
                    if (! acc.includes(elt) ){
                    acc.push(elt);
                    }
                }
            )
            return acc;
        },[]);

        return selectedToFilterRelation.map(
            selectedFilterId => this.state.filters[toFilter].reduce(
            (acc,filterValueAvailable) => {
                if( selectedFilterId == filterValueAvailable.id ){
                    return {'value': filterValueAvailable.id, 'label': filterValueAvailable.name}
                }
                return acc;
            },
            null
            )
            );

    }

    render(){

        let optionsFams =  this.state.filters['fam'].map( function( elt ) { return { "value":elt.id, "label": elt.name } } );
        let optionsCats =  this.state.filters['cat'].map( function( elt ) { if (this.state.currentFilters['fam'] == elt.parent){return { "value":elt.id, "label":elt.name }; } }, this ).filter(x => x != undefined);
        let optionsCity =  this.filterOptions('city');
        let optionsDeposits =  this.filterOptions('deposit');
        let optionsDeposit_types =  this.filterOptions('deposit_type');
        let filterCollapseClass= this.state.filterCollapse? "collapse-action closed":"collapse-action";

        let currentCategoryValue = null;
        if ( null !== this.state.currentFilters['cat'] ){ currentCategoryValue = this.state.currentFilters['cat'].map(elt => optionsCats.filter(opt => elt === opt.value))[0];}

        let currentDepositValue = null;
        if ( null !== this.state.currentFilters['deposit'] ){ currentDepositValue = this.state.currentFilters['deposit'].map(elt => optionsDeposits.filter(opt => elt === opt.value))[0];}

        let currentDeposit_typeValue = null;
        if ( null !== this.state.currentFilters['deposit_type'] ){ currentDeposit_typeValue = this.state.currentFilters['deposit_type'].map(elt => optionsDeposit_types.filter(opt => elt === opt.value))[0];}

        return (
        <>
            <div className="title" onClick={this.toggleFilters}>Filtres<span className={filterCollapseClass}>&gt;</span></div>
            <form className="filters-form" >
                <div className="form-inner">
                    <div className="filter-block">
                        <label className="materials-family-label control-title" htmlFor="materials-family-select">Familles</label>
                        <Select onChange={this.handleFamilyChange} options={optionsFams} placeholder="Sélectionner..." isClearable />
                    </div>
                    <div className="filter-block">
                        <label className="materials-category-label control-title" htmlFor="materials-category-select">Catégories</label>
                        <Select onChange={this.handleCategoryChange} options={optionsCats} isMulti={true} placeholder ="Sélectionner..." isClearable isDisabled={this.state.currentFilters['fam'] == null} value={currentCategoryValue} />
                    </div>
                    <div className="filter-block">
                        <label className="materials-city-label control-title" htmlFor="materials-city-select">Localisation</label>
                        <Select onChange={this.handleCityChange} options={optionsCity} isMulti={true} placeholder ="Sélectionner..." isClearable />
                    </div>
                    <div className="filter-block">
                        <label className="materials-deposits-label control-title" htmlFor="materials-deposit-select">Site</label>
                        <Select onChange={this.handleDepositChange} options={optionsDeposits} isMulti={true} placeholder ="Sélectionner..." isClearable  value={currentDepositValue} />
                    </div>
                    <div className="filter-block">
                        <label className="materials-deposit_types-label control-title" htmlFor="materials-deposit_types-select">Fournisseur</label>
                        <Select onChange={this.handleDeposit_typeChange} options={optionsDeposit_types} isMulti={true} placeholder ="Sélectionner..." isClearable  value={currentDeposit_typeValue} />
                    </div>
                    <div className="filter-block">
                        <label className="materials-availabality-label control-title" hmtlfor="materials-availabality-from">Dates de disponibilités</label>
                        <div className="filter-date"><span>Du</span><input type="date" name="materials-availabality-from" id="materials-availabality-from" /></div>
                        <div className="filter-date"><span>au</span><input type="date" name="materials-availabality-to" id="materials-availabality-to" /></div>
                    </div>
                    <div className="filter-block grid-70">
                        <span className="control-title" >Disponibilité</span>
                        <input type="checkbox" name="materials-in-stock" id="materials-in-stock" /><label hmtlfor="materials-in-stock">En stock</label>
                    </div>
                    <div className="filter-block">
                        <label className="materials-search-label control-title" hmtlfor="materials-search">Mots clés</label>
                        <input type="text" name="materials-search" id="materials-search" />                    
                    </div>
                    <div className="filter-validation"><a id="materials-filters-validation" className="filter-validation-btn"onClick={this.filterMaterials}>Filtrer</a></div>
                </div>
            </form>
        </>);
    }
}

class Sorting extends React.Component{

        static get sortingTypes () {return {'prop-title': "Désignation",'meta-deposit':"Site",'meta-availability_date':"Date"};} //
        static get sortingOrder () {return {'asc':"croissante",'desc':"décroissante"};}

        constructor(props) {
            super(props);   
            this.state={sortingCollapse:true, sort:'prop-title',order:'asc' };
            //window.addEventListener('resize',this.setSortingBoxBehaviours);
            //this.setSortingBoxBehaviours();
            
            //this.toFixSortingBox = this.toFixSortingBox.bind(this);
            this.setSortingBoxBehaviours = this.setSortingBoxBehaviours.bind(this);
            this.toggleSorting = this.toggleSorting.bind(this);
            this.handleSortingChange = this.handleSortingChange.bind(this);
            this.handleOrderingChange = this.handleOrderingChange.bind(this);
            this.dispatchUpdateSorting = this.dispatchUpdateSorting.bind(this);
        }

        componentDidMount(){
            
            eventBus.dispatch("updateSorting", {sort:this.state.sort,order:this.state.order});
        }


        handleSortingChange(e){            
            this.setState({sort:e.target.value});
            this.dispatchUpdateSorting( e.target.value, this.state.order );
        }

        handleOrderingChange(e){

            this.setState({order:e.target.value});
            this.dispatchUpdateSorting( this.state.sort, e.target.value );
        }

        dispatchUpdateSorting(sort,order){
            eventBus.dispatch("updateSorting", {sort:sort,order:order});
        }


        /**
         *  to handle scroll event on filter box according to viewport width
         */
         setSortingBoxBehaviours(){
            if (window.innerWidth > 991){
                document.addEventListener('scroll', this.toFixSortingBox);
                this.toFixSortingBox();
            }else{
                document.removeEventListener('scroll', this.toFixSortingBox);
            }
        }
        
        /**
         *  set filter box fixed according to scroll position
         */
        toFixSortingBox(){
            let sortingBlock = document.querySelector(".materials-sorting");
            let sortingBlockStyle = getComputedStyle(sortingBlock)
            let mainMenu = document.querySelector(".site-header");
            let wrappingSection = document.querySelector(".section-body .materials");
            let sideBar = document.querySelector(".aside-materials");
            if ((window.pageYOffset + mainMenu.offsetTop + mainMenu.offsetHeight) >= wrappingSection.offsetTop){
                sortingBlock.style.position = "fixed";
                sortingBlock.style.top = (mainMenu.offsetTop + mainMenu.offsetHeight + 20).toString()+"px";
                sortingBlock.style.width = (sideBar.offsetWidth - parseInt(sortingBlockStyle.marginRight)-parseInt(sortingBlockStyle.marginLeft)).toString()+"px";
            }else{
                sortingBlock.style.position = "relative";
                sortingBlock.style.top=0;
                sortingBlock.style.width = "100%";
            }
        }

        toggleSorting(){
            var panel = document.querySelector(".materials-sorting");
            panel.classList.toggle('collapsed');
            var formPanel = document.querySelector(".materials-sorting .sorting-form");
            if (formPanel.style.maxHeight !="0px" && formPanel.style.maxHeight != "") {
                formPanel.style.maxHeight = 0;
            } else {
                formPanel.style.maxHeight = formPanel.scrollHeight + "px";
            }
            this.setState({sortingCollapse:!this.state.sortingCollapse});
        }


        render(){

            let sortingCollapseClass= this.state.sortingCollapse? "collapse-action closed":"collapse-action";
            let sortingTypes = Sorting.sortingTypes;
            let sortingOrder = Sorting.sortingOrder;

            let options = Object.entries(sortingTypes).reduce(function(accType,type){
                    let value = `${type[0]}`;
                    let text = `${type[1]}`;
                return(<>{accType}<option value={value}>{text}</option></>);
            },(<></>))

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

            return(<>
             <div className="title" onClick={this.toggleSorting}>Tri <span className={sortingCollapseClass}>&gt;</span></div>
            <form className="sorting-form" >
                <div className="form-inner" >
                    <select onChange={this.handleSortingChange}>
                       {options}
                    </select>
                    <div className="sort-order-wrapper asc"><label htmlFor="sort-order-asc" className={sortOrderAscClass}>&gt;</label><input id='sort-order-asc' type="radio" name='order'  onChange={this.handleOrderingChange} value='asc' checked={ this.state.order === 'asc' }/></div>
                    <div className="sort-order-wrapper desc"><label htmlFor="sort-order-desc" className={sortOrderDescClass}>&gt;</label><input id='sort-order-desc' type="radio" name='order'  onChange={this.handleOrderingChange} value='desc' checked={ this.state.order === 'desc' }/></div>
                </div>                
            </form>
            </>)
        }
}



window.addEventListener('load', () => {	
    // Render the app inside our shortcode's #app div
    if (document.getElementById('materials-display') !=null){
        let perPages=9;
        ReactDom.render(
            <Materials perPages={perPages} />,
            document.getElementById('materials-display'));

        if (document.getElementById('materials-notifications') !=null){
            ReactDom.render(
                <CartNotifications />,
                document.getElementById('materials-notifications')
                );
        }
    }
    if (document.getElementById('materials-sorting') !=null){
        ReactDom.render(
        <Sorting />,
            document.getElementById('materials-sorting'));
    }
    if (document.getElementById('materials-filters') !=null){
        ReactDom.render(
        <Filters />,
            document.getElementById('materials-filters'));
    }

});

