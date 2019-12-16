
socket = {

    url: null,
    connection: null,

    onError: function(event) {
        console.log(event);
        render.showError('Не удается подключиться к сокету!');
        //render.showData(event);
        //var message = '<pre>' + JSON.stringify(event, "", 4) + '</pre>';
        //render.showMessage(message);
        socket.connection = null;
    },

    onOpen: function(event) {
        console.log(event);
        var text = 'Соединение установлено! Ожидаю события!';
        text = text + '<br/><code>' + socket.url + '</code>';
        render.showMessage(text);
        render.showOpenStatus();
    },

    onClose: function(event) {
        console.log(event);
        if (event.wasClean) {
            render.showMessage('Соединение закрыто');
            render.showCloseStatus();
        } else {
            render.showError('Обрыв соединения! Код: ' + event.code); // например, "убит" процесс сервера
            render.showAbortStatus();
            render.showCloseStatus();
        }
        socket.connection = null;
    },

    onMessage: function(event) {
        console.log(event);
        var data = JSON.parse(event.data);
        var text = 'Новое событие<br/> ' + '<pre>' + JSON.stringify(data, "", 4) + '</pre>';
        render.showMessage(text);
        console.log(data);
    },

    closeConnection: function() {
        console.log(socket.connection);
        if(socket.connection != null) {
            socket.connection.close();
        }
        socket.connection = null;
        render.clear();
        render.showCloseStatus();
    },

    getBaseWsUrl: function() {
        var formData = form.getData();
        var port = 8000;
        var socketHost = hostStore.oneSocketHostByUrl(formData.url);
        var hostString = socketHost + ':' + port;
        var url = hostString;
        return url;
    },

    onSuccessAuth: function(identity) {
        var formData = form.getData();
        var baseUrl = socket.getBaseWsUrl();
        render.showMessage('Аутентификация пройдена успешно!');
        var socketUrl = baseUrl + '?authorization=' + encodeURIComponent(identity.token) + '&events=' + formData.events;
        //var socketUrl = baseUrl + '/?login=' + formData.login + '&password=Wwwqqq111&events=' + formData.events;
        if(socket.connection != null) {
            socket.closeConnection();
        }
        render.showProgressStatus();
        socket.url = socketUrl;
        socket.connection = new WebSocket(socketUrl);
        socket.connection.onopen = socket.onOpen;
        socket.connection.onerror = socket.onError;
        socket.connection.onclose = socket.onClose;
        socket.connection.onmessage = socket.onMessage;
        render.showOpenStatus();
    },

    onFailAuth: function(event) {
        var body = event.responseJSON;
        var errors = [];
        for(var key in body) {
            var value = body[key];
            errors.push(value.message);
        }
        var message = 'Аутентификация не пройдена! <br/>' + errors.join('<br/>');
        render.showError(message);
    },

    openConnection: function() {
        render.clear();
        render.showProgressStatus();
        var formData = form.getData();
        var authPromise = auth.login(formData.login);
        authPromise.then(socket.onSuccessAuth);
        authPromise.catch(socket.onFailAuth);
    },

};
