define([
    "jquery",
    "underscore"
], function($, _){
    "use strict";
    $.widget("mr.configOptions", {
        options: {
            configurations: null,
            bundleOption: null,
            optionsFieldset: null,
            superAttributeSelector: ".super-attribute-select",
            selectionId: 0,
            optionId: 0,
            superAttributeSelectors: null,
            products: {},
            mainSelector: null
        },

        _create: function(){
            var self = this;
            $(self.options.mainSelector).on("change", self.updateSuperAttribute.bind(self));
            $(self.options.superAttributeSelector).on("change", self.optionChanged.bind(self));
        },

        updateSuperAttribute: function(event){

            var self = this;
            const selectionId = event.target.value

            if( self.options.selectionId == selectionId ) {
                const superAttributeSelectors = $('#options-'+selectionId).find("select");
                $(".bundle-configurable").addClass("no-display");
                $('#options-'+selectionId).removeClass("no-display");
                const attributeIds = [];



                _.each(superAttributeSelectors, function(superAttributeSelector, index){
                    const attributeId = $(superAttributeSelector).attr("data-attribute-id");
                    if(attributeId > 0) {
                        attributeIds.push(attributeId);
                    }
                });

                self.populateSelector(attributeIds, selectionId)
            }
        },

        optionChanged: function(event){
            var self = this
            const productsObject = self.options.configurations.index;
            const selectionId = event.target.getAttribute("data-selection-id");
            const selectors = $("#options-"+selectionId).find("select");
            const optionValues = [];

            selectors.each(function(){
                if($(this).val()) {
                    optionValues.push($(this).val());
                }
            });

            if(optionValues.length === selectors.length) {
                $('input[name="selection_configurable_option['+selectionId+']"').val(self.getProductId(productsObject, optionValues))
            }
        },

        getProductId: function(productsObject, optionValues) {
            let product = 0;
            _.each(productsObject, function(productObject, productId) {
                if(
                    JSON.stringify(_.toArray(productObject).sort()) ===
                    JSON.stringify(optionValues.sort())
                ) {
                    product = productId;
                }
            });

            return product;
        },

        extractAttributes: function(attributeId = 0) {
            if(attributeId > 0) {
                return this.options.configurations.attributes[attributeId];
            }
            return null;
        },

        populateSelector: function(attributeIds, selectionId){
            var self = this;
            _.each(attributeIds, function(attributeId, key){
                const attributeData = self.extractAttributes(attributeId);
                if(attributeData.id == attributeId) {

                    const selector = document.getElementById("attribute"+attributeData.id+"-"+selectionId)
                    selector.innerHTML = "";
                    let i = 0;
                    _.each(attributeData.options, function(option, index){
                        if(i == 0) {
                            const emptyOption = document.createElement("option")
                            emptyOption.value = "";
                            emptyOption.innerText = "--Please Select--";
                            selector.appendChild(emptyOption);
                        }

                        const opt = document.createElement("option")
                        opt.value = option.id;
                        opt.innerText = option.label
                        selector.appendChild(opt);
                        i++;
                    })
                }
            })
        }
    });

    return $.mr.configOptions;
});