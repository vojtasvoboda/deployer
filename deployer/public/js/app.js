$(document).ready(function() {

    var consoleOutput = $('#console-output');
    consoleOutput.hide();

	$('form').each(function() {
		var form = $(this),
		    action = form.attr('action'),
		    method = form.attr('method') || 'post';

		var doSubmit = function(e, btn) {
			e.preventDefault();

			var data = form.serializeArray();

			if (btn.attr('name')) {
				data.push({
					name:  btn.attr('name'),
					value: btn.attr('value')
				});
			}

            var req = createRequest();
			req.open(method, action, true);
			req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			req.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			req.send($.param(data));
		};

		var buttons = form.find('button[type="submit"], input[type="submit"]');

		form.submit(function(e) { doSubmit(e, buttons.first()); });
		buttons.click(function(e) { doSubmit(e, $(this)); });

		return this;
	});

    var createRequest = function(consoleOutputEl) {
        var req = new XMLHttpRequest();

        req.onreadystatechange = initialResponseHandler;

        return req;
    };

    var initialResponseHandler = function()
    {
        if (this.readyState != 2) {
            return;
        }

        if (this.getResponseHeader('Content-type') == 'application/json') {
            this.onreadystatechange = jsonResponseHandler

        } else {
            consoleOutput.html('').addClass('running').show();
            this.onreadystatechange = consoleResponseHandler
        }
    };

    var consoleResponseHandler = function()
    {
        var doScroll = $(document).scrollTop() != $(document).height() - $(window).height();

        consoleOutput.html(this.responseText);

        if (doScroll) {
            $(document).scrollTop($(document).height());
        }

        if (this.readyState == 4) {
            consoleOutput.removeClass('running');
        }
    };

    var jsonResponseHandler = function()
    {
        if (this.readyState < 4) {
            return;
        }

        console.log('json');
    };
});
