'use strict';

/* Directives */

angular.module('podcatcherDirectives', []).
directive('appVersion', ['version', function(version) {
    return function(scope, elm, attrs) {
        elm.text(version);
    };
}]).
directive('isoGrid',function()
{
    return function(scope,element,attrs)
    {
//             $("#thumb-view").isotope({ 
//                   itemSelector: '.view',
//                   containerStyle: { position: 'relative', overflow: 'visible' },
//                   masonry: {
//                       columnWidth: 5
//                   }
//               });
    };
});