/**
 * Created by petr on 26.3.16.
 */


var map;
Array.prototype.forEach.call(document.getElementsByClassName('googleMapAPI'), function(el, i){
    map = new GoogleMap(el);
    map.doProportions();

    if (typeof google === "undefined") {
        loadScript();
    } else {
        map.initialize();
    }
});