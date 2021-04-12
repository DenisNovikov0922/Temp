(function($) { 
  "use strict"; 

var RpCheckoutAutocomplete = RpCheckoutAutocomplete || {};
var RpCheckoutAutocomplete_shipping = RpCheckoutAutocomplete_shipping || {};
RpCheckoutAutocomplete.event = {};
RpCheckoutAutocomplete_shipping.event = {};
RpCheckoutAutocomplete.method = {
    placeSearch: "",
    IdSeparator: "",
    autocomplete : "",
    streetNumber : "",
    formFields : {
        'billing_address_1': '',
        'billing_address_2': '',
        'billing_city': '',
        'billing_state': '',
        'billing_postcode': '',
        'billing_country' : ''
    },
    formFieldsValue : {
        'billing_address_1': '',
        'billing_address_2': '',
        'billing_city': '',
        'billing_state': '',
        'billing_postcode': '',
        'billing_country' : ''
    },
    component_form : "",

    initialize: function(){
        this.getIdSeparator();
        this.initFormFields();

        this.autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById('billing_address_1')),
            {
                types: ['geocode']
            });
        google.maps.event.addListener(this.autocomplete, 'place_changed', function( event ) {
            RpCheckoutAutocomplete.method.fillInAddress()
        });
        var billing_address = document.getElementById("billing_address_1");
        if(billing_address != null){
            billing_address.addEventListener("focus", function( event ) {
                RpCheckoutAutocomplete.method.setAutocompleteCountry()
            }, true);
        } 

        var billing_country = document.getElementById("billing_country");
        if(billing_country != null){
            billing_country.addEventListener("change", function( event ) {
                RpCheckoutAutocomplete.method.setAutocompleteCountry()
            }, true);
        }
       

    },
    getIdSeparator : function() {
        if (!document.getElementById('billing_address_1')) {
            this.IdSeparator = "_";
            return "_";
        }
        this.IdSeparator = ":";
        return ":";
    },
    initFormFields: function ()
    {
        for (var field in this.formFields) {
            this.formFields[field] = (field);
        }
        this.component_form =
        {
            'street_number': ['billing_address_1', 'short_name'],
            'route': ['billing_address_1', 'long_name'],
            'locality': ['billing_city', 'long_name'],
            'administrative_area_level_1': ['billing_state', 'short_name'],
            'country': ['billing_country', 'long_name'],
            'postal_code': ['billing_postcode', 'short_name']
        };
    },
    
    fillInAddress : function () {
        this.clearFormValues();
        var place = this.autocomplete.getPlace();
        this.resetForm();
        var type = '';
        for (var field in place.address_components) {
            for (var t in  place.address_components[field].types)
            {
                for (var f in this.component_form) {
                    var types = place.address_components[field].types;
                    if(f == types[t])
                    {
                        if(f == "street_number")
                        {
                            this.streetNumber = place.address_components[field]['short_name'];
                        }else{
                            
                            if(document.getElementById("billing_country").value=="KR"){
                                this.streetNumber=place.address_components[0]['short_name'];
                                this.streetNumber+=","+place.address_components[1]['long_name'];
                            }
                        }

                        var prop = this.component_form[f][1];
                        if(place.address_components[field].hasOwnProperty(prop)){
                            this.formFieldsValue[this.component_form[f][0]] = place.address_components[field][prop];
                        }

                    }
                }
            }
        }

        this.appendStreetNumber();
        this.fillForm();
        $=jQuery.noConflict();
        $("#billing_state").trigger("change");
        if(typeof  FireCheckout !== 'undefined')
        {
            checkout.update(checkout.urls.billing_address);
        }
    },

    clearFormValues: function ()
    {
        for (var f in this.formFieldsValue) {
            this.formFieldsValue[f] = '';
        }
    },
    appendStreetNumber : function ()
    {
        if(this.streetNumber != '')
        {
            this.formFieldsValue['billing_address_1'] =  this.streetNumber + ' '
            + this.formFieldsValue['billing_address_1'];
        }
    },
    fillForm : function()
    {
        for (var f in this.formFieldsValue) {
            if(f == 'billing_country' )
            {
                this.selectRegion( f,this.formFieldsValue[f]);
            }
            else
            {
                if(document.getElementById((f)) === null){
                    continue;
                }
                else
                {
                    document.getElementById((f)).value = this.formFieldsValue[f];
                }
              
            }
        } 
    },
    selectRegion:function (id,regionText)
    {
        if(document.getElementById((id)) == null){
            return false;
        } 
        var el = document.getElementById((id));
        for(var i=0; i<el.options.length; i++) {
            if ( el.options[i].text == regionText ) {
                el.selectedIndex = i;
                break;
            }
        }
    },
    resetForm :function ()
    {
        if(document.getElementById(('billing_address_2')) !== null){
            document.getElementById(('billing_address_2')).value = '';
        }   
    },


    setAutocompleteCountry : function () {
      
        if(document.getElementById('billing_country') === null){
            country = 'US';
        }
        else
        {
            var country = document.getElementById('billing_country').value;
        }
        this.autocomplete.setComponentRestrictions({
            'country': country
        });
    }


}


RpCheckoutAutocomplete_shipping.method = {
    placeSearch: "",
    IdSeparator: "",
    autocomplete : "",
    streetNumber : "",
    formFields : {
        'shipping_address_1': '',
        'shipping_address_2': '',
        'shipping_city': '',
        'shipping_state': '',
        'shipping_postcode': '',
        'shipping_country' : ''
    },
    formFieldsValue : {
        'shipping_address_1': '',
        'shipping_address_2': '',
        'shipping_city': '',
        'shipping_state': '',
        'shipping_postcode': '',
        'shipping_country' : ''
    },
    component_form : "",

    initialize: function(){
        this.getIdSeparator();
        this.initFormFields();

        this.autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById('shipping_address_1')),
            {
                types: ['geocode']
            });
        google.maps.event.addListener(this.autocomplete, 'place_changed', function( event ) {
            RpCheckoutAutocomplete_shipping.method.fillInAddress()
        });
        var shipping_address = document.getElementById("shipping_address_1");
        if(shipping_address != null){
            shipping_address.addEventListener("focus", function( event ) {
                RpCheckoutAutocomplete_shipping.method.setAutocompleteCountry()
            }, true);
        } 

        var shipping_country = document.getElementById("shipping_country");
        if(shipping_country != null){
            shipping_country.addEventListener("change", function( event ) {
                RpCheckoutAutocomplete_shipping.method.setAutocompleteCountry()
            }, true);
        }
       

    },
    getIdSeparator : function() {
        if (!document.getElementById('shipping_address_1')) {
            this.IdSeparator = "_";
            return "_";
        }
        this.IdSeparator = ":";
        return ":";
    },
    initFormFields: function ()
    {
        for (var field in this.formFields) {
            this.formFields[field] = (field);
        }
        this.component_form =
        {
            'street_number': ['shipping_address_1', 'short_name'],
            'route': ['shipping_address_1', 'long_name'],
            'locality': ['shipping_city', 'long_name'],
            'administrative_area_level_1': ['shipping_state', 'short_name'],
            'country': ['shipping_country', 'long_name'],
            'postal_code': ['shipping_postcode', 'short_name']
        };
    },
    
    fillInAddress : function () {
        this.clearFormValues();
        var place = this.autocomplete.getPlace();
        this.resetForm();
        var type = '';
        for (var field in place.address_components) {
            for (var t in  place.address_components[field].types)
            {
                for (var f in this.component_form) {
                    var types = place.address_components[field].types;
                    if(f == types[t])
                    {
                        if(f == "street_number")
                        {
                            this.streetNumber = place.address_components[field]['short_name'];
                        }else{
                            
                            if(document.getElementById("shipping_country").value=="KR"){
                                this.streetNumber=place.address_components[0]['short_name'];
                                this.streetNumber+=","+place.address_components[1]['long_name'];
                            }
                        }

                        var prop = this.component_form[f][1];
                        if(place.address_components[field].hasOwnProperty(prop)){
                            this.formFieldsValue[this.component_form[f][0]] = place.address_components[field][prop];
                        }

                    }
                }
            }
        }

        this.appendStreetNumber();
        this.fillForm();
        
        
        $=jQuery.noConflict();
        $("#shipping_state").trigger("change");
        if(typeof  FireCheckout !== 'undefined')
        {
            checkout.update(checkout.urls.shipping_address);
        }
    },

    clearFormValues: function ()
    {
        for (var f in this.formFieldsValue) {
            this.formFieldsValue[f] = '';
        }
    },
    appendStreetNumber : function ()
    {
        if(this.streetNumber != '')
        {
            this.formFieldsValue['shipping_address_1'] =  this.streetNumber + ' '
            + this.formFieldsValue['shipping_address_1'];
        }
    },
    fillForm : function()
    {
        for (var f in this.formFieldsValue) {
            if(f == 'shipping_country' )
            {
                this.selectRegion( f,this.formFieldsValue[f]);
            }
            else
            {
                if(document.getElementById((f)) === null){
                    continue;
                }
                else
                {
                    document.getElementById((f)).value = this.formFieldsValue[f];
                }
              
            }
        } 
    },
    selectRegion:function (id,regionText)
    {
        if(document.getElementById((id)) == null){
            return false;
        } 
        var el = document.getElementById((id));
        for(var i=0; i<el.options.length; i++) {
            if ( el.options[i].text == regionText ) {
                el.selectedIndex = i;
                break;
            }
        }
    },
    resetForm :function ()
    {
        if(document.getElementById(('shipping_address_2')) !== null){
            document.getElementById(('shipping_address_2')).value = '';
        }   
    },


    setAutocompleteCountry : function () {
      
        if(document.getElementById('shipping_country') === null){
            country = 'US';
        }
        else
        {
            var country = document.getElementById('shipping_country').value;
        }
        this.autocomplete.setComponentRestrictions({
            'country': country
        });
    }


}


window.addEventListener('load', function(){
    if(!(document.getElementById('billing_address_1') === null))
        RpCheckoutAutocomplete.method.initialize();
    if(!(document.getElementById('shipping_address_1') === null))
        RpCheckoutAutocomplete_shipping.method.initialize();
});

if(!(document.getElementById('billing_address_1') === null)){
    var billaddr = document.getElementById('billing_address_1');
    google.maps.event.addDomListener(billaddr, 'keydown', function(e) { 
        if (e.keyCode == 13) { 
            e.preventDefault(); 
        }
    }); 
}

if(!(document.getElementById('shipping_address_1') === null)){
    var shipaddr = document.getElementById('shipping_address_1');
    google.maps.event.addDomListener(shipaddr, 'keydown', function(e) { 
        if (e.keyCode == 13) { 
            e.preventDefault(); 
        }
    }); 
}

    })(jQuery);