define([
    'jquery',
    'Magento_Ui/js/form/element/multiselect'
], function ($, MultiSelect) {
    'use strict';

    return MultiSelect.extend({
        defaults: {
            url: '',
            product_id: '',
            selection_id: ''
        },

        initObservable: function () {
            this._super();

            var self = this;
            setTimeout(function () {
                self.configOptions(self.selection_id, self.product_id);
            }, 0);

            return this;
        },


        configOptions: function (selectionId, productId) {
            var self = this;
            $.ajax({
                url: this.url,
                data: {
                    selection_id: selectionId,
                    product_id: productId
                },
                type: 'GET',
                dataType: 'JSON',
                showLoader: true,

                success: $.proxy(function (response) {
                    if (response.length > 0) {
                        var selected = [];
                        $.each(response, function (index, option) {
                            if (option.selected) {
                                selected.push(option.value);
                            }
                        });
                        self.default = selected.join(',');
                        self.setOptions(response);
                        self.setInitialValue();
                    }
                }, this)
            });
        }
    });
});
