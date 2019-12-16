
hostStore = {

    all: function () {
        return host;
    },

    oneSocketHostByUrl: function (url) {
        var hostData = host[url];
        return hostData.socket;
    },

    oneApiHostByUrl: function (url) {
        var hostData = host[url];
        return hostData.api;
    },

};
