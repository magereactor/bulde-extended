define([
    'underscore'
], function (_) {
    'use strict';

    var mixin = {
        initElements: function (data) {
            if (!_.isArray(data)) {
                data = [data];
            }

            return this._super(data);
        }
    };

    return function (target) {
        return target.extend(mixin);
    }
});
