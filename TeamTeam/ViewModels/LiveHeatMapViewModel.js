//the view model
var LiveHeatMapViewModel = function() {
    self = this;

    //time variables
    self.year = ko.observable('');
    self.month = ko.observable('');
    self.day = ko.observable('');
    self.hour = ko.observable('');
    self.minute = ko.observable('');
    self.second = ko.observable('');

    self.futureyear = ko.observable('');
    self.futuremonth = ko.observable('');
    self.futureday = ko.observable('');
    self.futurehour = ko.observable('');
    self.futureminute = ko.observable('');
    self.futuresecond = ko.observable('');

    self.time = ko.observable(1);
    self.interval = ko.observable("0");
    self.displayedTime = ko.observable();
    self.playText = ko.observable("Play");

    self.width = ko.observable(1024);
    self.height = ko.observable(720);
    self.radius = ko.observable(550);

    self.url = ko.observable();

    self.min = ko.observable(60);
    self.max = ko.observable(85);

    self.result = ko.observable();

    //node values
    self.tempNode1 = ko.observable(0);
    self.tempNode2 = ko.observable(0);
    self.tempNode3 = ko.observable(0);
    self.tempNode4 = ko.observable(0);
    self.tempNode5 = ko.observable(0);
    self.tempNode6 = ko.observable(0);

    self.selectedUnit = ko.observable("Fahrenheit");

    self.selectedUnit.subscribe(function (newValue) {
        if (newValue == "Celsius") {
            self.min(15.56);
            self.max(29.4);
            self.tempNode1(Math.round((self.tempNode1() - 32) * 5 / 9 * 100) / 100);
            self.tempNode2(Math.round((self.tempNode2() - 32) * 5 / 9 * 100) / 100);
            self.tempNode3(Math.round((self.tempNode3() - 32) * 5 / 9 * 100) / 100);
            self.tempNode4(Math.round((self.tempNode4() - 32) * 5 / 9 * 100) / 100);
            self.tempNode5(Math.round((self.tempNode5() - 32) * 5 / 9 * 100) / 100);
            self.tempNode6(Math.round((self.tempNode6() - 32) * 5 / 9 * 100) / 100);
        }
        if (newValue == "Fahrenheit") {
            self.min(60);
            self.max(85);
            self.tempNode1(Math.round((self.tempNode1() * 9 / 5 + 32) * 100) / 100);
            self.tempNode2(Math.round((self.tempNode2() * 9 / 5 + 32) * 100) / 100);
            self.tempNode3(Math.round((self.tempNode3() * 9 / 5 + 32) * 100) / 100);
            self.tempNode4(Math.round((self.tempNode4() * 9 / 5 + 32) * 100) / 100);
            self.tempNode5(Math.round((self.tempNode5() * 9 / 5 + 32) * 100) / 100);
            self.tempNode6(Math.round((self.tempNode6() * 9 / 5 + 32) * 100) / 100);
        }
    });
    
    self.tempNode1.subscribe(function (newValue) {
        initialize();
    });

    self.tempNode2.subscribe(function (newValue) {
        initialize();
    });

    self.tempNode3.subscribe(function (newValue) {
        initialize();
    });

    self.tempNode4.subscribe(function (newValue) {
         initialize();
    });

    self.tempNode5.subscribe(function (newValue) {
        initialize();
    });

    self.tempNode6.subscribe(function (newValue) {
          initialize();
    });

    self.interval.subscribe(function (newValue) {
        initialize();
    });

    self.time.subscribe(function (newValue) {
        initialize();
    });

    self.playClicked = function () {
        if (self.playText() == "Pause") {
            self.playText("Play");
        }
        else { //playText() == "Pause"
            self.playText("Pause");
            self.incrementInterval();
        }
    }

    self.incrementInterval = function () {
        if (self.playText() == "Pause") {
            if (self.interval() == "0")
                self.interval("9");
            else if (self.interval() == "9")
                self.interval("8");
            else if (self.interval() == "8")
                self.interval("7");
            else if (self.interval() == "7")
                self.interval("6");
            else if (self.interval() == "6")
                self.interval("5");
            else if (self.interval() == "5")
                self.interval("4");
            else if (self.interval() == "4")
                self.interval("3");
            else if (self.interval() == "3")
                self.interval("2");
            else if (self.interval() == "2")
                self.interval("1");
            else if (self.interval() == "1") {
                self.interval("0");
                setTimeout(self.incrementInterval, 3000);
                return;
            }
            setTimeout(self.incrementInterval, 1000);
        }
    }

    Array.prototype.remove = function () {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };
    

    self.searchTemp = function () {
        var currentDate = new Date();
        currentDate.setSeconds(currentDate.getSeconds() - 60);
        currentDate.setHours(currentDate.getHours() - (self.interval() * self.time()));
        self.displayedTime(currentDate);

        //greater than this date
        self.year(currentDate.getUTCFullYear());
        self.month(currentDate.getUTCMonth()+1);
        self.day(currentDate.getUTCDate());
        self.hour(currentDate.getUTCHours());
        self.minute(currentDate.getUTCMinutes());
        self.second(currentDate.getUTCSeconds());

        //less than this date
        currentDate.setSeconds(currentDate.getSeconds() + 60);
        self.futureyear(currentDate.getUTCFullYear());
        self.futuremonth(currentDate.getUTCMonth() + 1);
        self.futureday(currentDate.getUTCDate());
        self.futurehour(currentDate.getUTCHours());
        self.futureminute(currentDate.getUTCMinutes());
        self.futuresecond(currentDate.getUTCSeconds());

        if (self.second().toString().length == 1)
            self.second('0' + self.second());

        if (self.minute().toString().length == 1)
            self.minute('0' + self.minute());

        if (self.hour().toString().length == 1)
            self.hour('0' + self.hour());

        if (self.day().toString().length == 1)
            self.day('0' + self.day());

        if (self.month().toString().length == 1)
            self.month('0' + self.month());

        if (self.futuresecond().toString().length == 1)
            self.futuresecond('0' + self.futuresecond());

        if (self.futureminute().toString().length == 1)
            self.futureminute('0' + self.futureminute());

        if (self.futurehour().toString().length == 1)
            self.futurehour('0' + self.futurehour());

        if (self.futureday().toString().length == 1)
            self.futureday('0' + self.futureday());

        if (self.futuremonth().toString().length == 1)
            self.futuremonth('0' + self.futuremonth());

        self.url('https://influx.roomtemp.net:8086/query?db=servicedashboard&q=SELECT+tempc+FROM+temperature,host+WHERE+time+%3E+' + 
            '%27' +
            self.year().toString() +
            '-' +
            self.month().toString() +
            '-' +
            self.day().toString() + //date
            'T' +
            self.hour().toString() + //hour
            '%3A' +
            self.minute().toString() + //minute
            '%3A' +
            self.second().toString() +
            '.000000000Z%27+AND+time+<+' +
            '%27' +
            self.futureyear().toString() +
            '-' +
            self.futuremonth().toString() +
            '-' +
            self.futureday().toString() + //date
            'T' +
            self.futurehour().toString() + //hour
            '%3A' +
            self.futureminute().toString() + //minute
            '%3A' +
            self.futuresecond().toString() +
            '.000000000Z%27+GROUP+BY+host');

        $.ajax({
            url: self.url(),
            dataType: "json",
            success: function (data) {
                self.result(data);
                //self.temp(self.result().results[0].series[0].values[0][1]);

                if (self.selectedUnit() == "Celsius") {
                    if (self.result().results[0].series != null) {
                        nodes = ['tempNode1', 'tempNode2', 'tempNode3', 'tempNode4', 'tempNode5', 'tempNode6'];
                        for (i = 0; i < self.result().results[0].series.length; i++) {
                            if (self.result().results[0].series[i].tags.host == 'tempNode1') {
                                nodes.remove('tempNode1');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode1(self.result().results[0].series[i].values[0][1])
                                else {
                                    self.tempNode1(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode2') {
                                nodes.remove('tempNode2');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode2(self.result().results[0].series[i].values[0][1])
                                else {
                                    self.tempNode2(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode3') {
                                nodes.remove('tempNode3');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode3(self.result().results[0].series[i].values[0][1])
                                else {
                                    self.tempNode3(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode4') {
                                nodes.remove('tempNode4');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode4(self.result().results[0].series[i].values[0][1])
                                else {
                                    self.tempNode4(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode5') {
                                nodes.remove('tempNode5');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode5(self.result().results[0].series[i].values[0][1])
                                else {
                                    self.tempNode5(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode6') {
                                nodes.remove('tempNode6');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode6(self.result().results[0].series[i].values[0][1])
                                else {
                                    self.tempNode6(0);
                                }
                            }
                        }
                            if (nodes != null) {
                                for (j = 0; j < nodes.length; j++) {
                                    if (nodes[j] == 'tempNode1') {
                                        self.tempNode1(0);
                                    }
                                    if (nodes[j] == 'tempNode2') {
                                        self.tempNode2(0);
                                    }
                                    if (nodes[j] == 'tempNode3') {
                                        self.tempNode3(0);
                                    }
                                    if (nodes[j] == 'tempNode4') {
                                        self.tempNode4(0);
                                    }
                                    if (nodes[j] == 'tempNode5') {
                                        self.tempNode5(0);
                                    }
                                    if (nodes[j] == 'tempNode6') {
                                        self.tempNode6(0);
                                    }
                                }
                            }
                    }
                }
                else {
                    if (self.result().results[0].series != null) {
                        nodes = ['tempNode1', 'tempNode2', 'tempNode3', 'tempNode4', 'tempNode5', 'tempNode6'];
                        for (i = 0; i < self.result().results[0].series.length; i++) {
                            if (self.result().results[0].series[i].tags.host == 'tempNode1') {
                                nodes.remove('tempNode1');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode1(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                                else {
                                    self.tempNode1(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode2') {
                                nodes.remove('tempNode2');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode2(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                                else {
                                    self.tempNode2(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode3') {
                                nodes.remove('tempNode3');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode3(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                                else {
                                    self.tempNode3(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode4') {
                                nodes.remove('tempNode4');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode4(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                                else {
                                    self.tempNode4(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode5') {
                                nodes.remove('tempNode5');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode5(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                                else {
                                    self.tempNode5(0);
                                }
                            }
                            if (self.result().results[0].series[i].tags.host == 'tempNode6') {
                                nodes.remove('tempNode6');
                                if (self.result().results[0].series[i].values != null)
                                    self.tempNode6(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                                else {
                                    self.tempNode6(0);
                                }
                            }
                        }
                            if (nodes != null) {
                                for (j = 0; j < nodes.length; j++) {
                                    if (nodes[j] == 'tempNode1') {
                                        self.tempNode1(0);
                                    }
                                    if (nodes[j] == 'tempNode2') {
                                        self.tempNode2(0);
                                    }
                                    if (nodes[j] == 'tempNode3') {
                                        self.tempNode3(0);
                                    }
                                    if (nodes[j] == 'tempNode4') {
                                        self.tempNode4(0);
                                    }
                                    if (nodes[j] == 'tempNode5') {
                                        self.tempNode5(0);
                                    }
                                    if (nodes[j] == 'tempNode6') {
                                        self.tempNode6(0);
                                    }
                                }
                            }
                    }
                }

                },
            error: function (data) {
                self.result(data);
            }
        });

        if(self.interval() == "0")
            setTimeout(self.searchTemp, 5000);
    }


    self.heatmap = h337.create({
        container: document.getElementById('heatmapContainer'),
        gradient:
        {
            0.15: "rgb(0,0,255)",
            0.50: "rgb(0,255,0)",
            0.70: "rgb(255, 165, 0)",
            1.00: "rgb(255,0,0)"
        }
    });

    initialize = function () {
        // boundaries for data generation
        self.searchTemp();

        self.heatmap.setData({
            min: self.min(),
            max: self.max(),
            data: [
                {//Node 1
                    x: self.width() / 16,
                    y: self.height() / 8,
                    value: self.tempNode1(),
                    radius: self.radius()

                },
                {//Node 2
                    x: self.width() / 2,
                    y: self.height() / 8,
                    value: self.tempNode2(),
                    radius: self.radius()
                },
                {//Node 3
                    x: self.width() / 16 * 15,
                    y: self.height() / 8,
                    value: self.tempNode3(),
                    radius: self.radius()
                },
                {//Node 4
                    x: self.width() / 16,
                    y: self.height() / 8 * 7,
                    value: self.tempNode4(),
                    radius: self.radius()
                },
                {//Node 5
                    x: self.width() / 2,
                    y: self.height() / 8 * 7,
                    value: self.tempNode5(),
                    radius: self.radius()
                },
                {//Node 6
                    x: self.width() / 16 * 15,
                    y: self.height() / 8 * 7,
                    value: self.tempNode6(),
                    radius: self.radius()
                }
            ]
        });
    }

    initialize();
};

//binding the viewmodel to the view
ko.applyBindings(new LiveHeatMapViewModel());
