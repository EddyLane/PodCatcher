requirejs({
    "baseUrl": "../../",
    "paths": {
        "config": "config",
        "angular": "lib/angular",
        "jQuery": "lib/jquery",
        "domReady": "lib/domReady",
        "app": "app"
    },
    "shim": {
        "jQuery": {"exports": "jQuery"},
        "angular": {
            "deps" : ["jQuery"],
            "exports": "angular"
        }
    },
    priority: [
        "angular"
    ],
    urlArgs: "v=0.1"
},["angular","jQuery", "app", "config", "routes", "services/services", "directives/directives", "providers/providers",
    "filters/filters", "controllers/controllers", "animations/animations"],function (angular) {
    return angular.bootstrap(document, ["myApp"]);
});