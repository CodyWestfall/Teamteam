//the view model
var LiveHumidityMapViewModel = function() {
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

    self.width = ko.observable(400);
    self.height = ko.observable(400);
    self.radius = ko.observable(250);

    self.url = ko.observable();

    self.min = ko.observable(0);
    self.max = ko.observable(50);

    self.result = ko.observable();

    //node values
    self.nodeArray = ko.observableArray([]);

    self.nodeArray(function (newValue) {
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

    self.getNodes = function () {
        self.tempArray = ko.observableArray([]);
        $(".node").each(function (index) {
            self.tempArray.push({ index: index, 
                                  x: $(this).position().left, 
                                  y: $(this).position().top, 
                                  id: $(this).attr("alt"),
                                  temp: ko.observable(0)});
        });
        self.nodeArray(self.tempArray());
    }

    self.updateNodePosition = function () {
        $(".node").each(function (index) {
            for (i = 0; i < self.nodeArray().length; i++) {
                if (self.nodeArray()[i] != null) {
                    if (self.nodeArray()[i].index == index) {
                        self.nodeArray()[i].x = $(this).position().left;
                        self.nodeArray()[i].y = $(this).position().top;
                    }
                }
            }
        });
    }

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

        self.url('https://influx.roomtemp.net:8086/query?db=servicedashboard&q=SELECT+humidity+FROM+temperature,host+WHERE+time+%3E+' + 
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
            '.000000000Z%27+GROUP+BY+serial');

        $.ajax({
            url: self.url(),
            dataType: "json",
            success: function (data) {
                self.result(data);
                tempNodes = [];
                for (k = 0; k < self.nodeArray().length; k++) {
                    tempNodes.push(self.nodeArray()[k]);
                }
                if (self.result().results[0].series != null) {
                    for (i = 0; i < self.result().results[0].series.length; i++) {
                        for (j = 0; j < self.nodeArray().length; j++) {
                            if (self.result().results[0].series[i].tags.serial == self.nodeArray()[j].id) {
                                tempNodes.remove(self.nodeArray()[j]);
                                if (self.result().results[0].series[i].values != null)
                                    self.nodeArray()[j].temp(self.result().results[0].series[i].values[0][1])
                                else {
                                    self.nodeArray()[j].temp(0);
                                }
                            }
                        }
                    }
                    if (tempNodes.length > 0) {
                        for (j = 0; j < tempNodes.length; j++) {
                            node = {
                                index: tempNodes[j].index,
                                x: tempNodes[j].x,
                                y: tempNodes[j].y,
                                id: tempNodes[j].id,
                                temp: ko.observable(0)
                            }
                            self.nodeArray.replace(tempNodes[j], node);
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
        container: document.getElementById('heatmapdiv'),
        gradient:
        {//15, 50, 70, 100
            0.15: "rgb(255,0,0)",
            0.50: "rgb(255, 165, 0)",
            0.70: "rgb(0,255,0)",
            1.00: "rgb(0,0,255)"
        }
    });

    initialize = function () {
        // boundaries for data generation
        self.width($(drawArea).width());
        self.height($(drawArea).height());

        $(".heatmapdiv").width($("#drawArea").width());
        $(".heatmapdiv").height($("#drawArea").height());
        $('.heatmap-canvas').position("relative");
        $('.heatmap-canvas').css('zIndex', '-1');

        if ($(".node").length != self.nodeArray().length)
            self.getNodes();
        self.updateNodePosition();
        self.searchTemp();
        mapdata = [];
        $('.tempText').each(function (index) {
            $(this).remove();
        });
        for (i = 0; i < self.nodeArray().length; i++) {
            mapdata.push({
                x: self.nodeArray()[i].x,
                y: self.nodeArray()[i].y,
                value: self.nodeArray()[i].temp(),
                radius: self.radius()
            });
            $('#heatmapdiv').append("<p id='tempText" + i + "' class='tempText' style='position: absolute; left: " + self.nodeArray()[i].x + "px; top: " + self.nodeArray()[i].y + "px; color: white; font-weight: bold; transform: translate(-50%, -50%);'></p>")
            $('#tempText' + i).text(self.nodeArray()[i].temp() + '%');
        }

        self.heatmap.setData({
            min: self.min(),
            max: self.max(),
            data: mapdata
        });

    }

    self.getNodes();

    initialize();

    self.playClicked();
};

//binding the viewmodel to the view
ko.applyBindings(new LiveHumidityMapViewModel());
