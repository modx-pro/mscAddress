(function (window, document, $, mscaConfig) {
	var msca = msca || {};
	
	mscaConfig.callbacksTemplate = function () {
        return {
            before: [],
            response: {
                success: [],
                error: []
            }
        }
    };
    msca.Callbacks = mscaConfig.Callbacks = {
        Code: {
            add: mscaConfig.callbacksTemplate(),
            getcart: mscaConfig.callbacksTemplate(),
            clean: mscaConfig.callbacksTemplate(),
            addField: mscaConfig.callbacksTemplate()
        },
        Share: {
            email: mscaConfig.callbacksTemplate(),
            popup: mscaConfig.callbacksTemplate()
        },
    };
	
    msca.setup = function () {
		this.form = '.msca_form';
		this.actionName = 'msca_action';
		this.action = '[name=' + this.actionName + ']:enabled';
        this.sendData = {
            $form: null,
            action: null,
            formData: null
        };
    };
	
	msca.send = function (data, callbacks) {
		
        var runCallback = function (callback, bind) {
            if (typeof callback == 'function') {
                return callback.apply(bind, Array.prototype.slice.call(arguments, 2));
            }
            else if (typeof callback == 'object') {
                for (var i in callback) {
                    if (callback.hasOwnProperty(i)) {
                        var response = callback[i].apply(bind, Array.prototype.slice.call(arguments, 2));
                        if (response === false) {
                            return false;
                        }
                    }
                }
            }
            return true;
        };
        // set context
        if ($.isArray(data)) {
            data.push({
                name: 'ctx',
                value: mscaConfig.ctx
            });
			data.push({
                name: 'hash_key',
                value: mscaConfig.hash_key
            });
        }
        else if ($.isPlainObject(data)) {
            data.ctx = mscaConfig.ctx;
            data.hash_key = mscaConfig.hash_key;
        }
        else if (typeof data == 'string') {
            data += '&ctx=' + mscaConfig.ctx + '&hash_key=' + mscaConfig.hash_key;
        }

        // set action url
        var formActionUrl = (msca.sendData.$form)
            ? msca.sendData.$form.attr('action')
            : false;
        var url = (formActionUrl)
            ? formActionUrl
            : (mscaConfig.actionUrl)
                      ? mscaConfig.actionUrl
                      : document.location.href;
        // set request method
        var formMethod = (msca.sendData.$form)
            ? msca.sendData.$form.attr('method')
            : false;
        var method = (formMethod)
            ? formMethod
            : 'post';

        // callback before
        if (runCallback(callbacks.before) === false) {
            return;
        }
		$.ajax({
			type: method,
			url: url,
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			success: function(response){
				if(response.success){
                    if (response.message) {
                        miniShop2.Message.success(response.message);
                    }
                    runCallback(callbacks.response.success, msca, response);
				}
				else{
					miniShop2.Message.error(response.message);
                    runCallback(callbacks.response.error, msca, response);
				}
			}
		});
    };
	
	msca.initialize = function() {
		msca.setup();
		
		$(document).on('submit', msca.form, function (e) {
			e.preventDefault();
			var $form = $(this);
			var action = $form.find( msca.action ).val();
			if (action) {
				var formData = $form.serializeArray();
				formData.push({
					name: msca.actionName,
					value: action
				});
				msca.sendData = {
					$form: $form,
					action: action,
					formData: formData
				};
				msca.controller();
			}
		});
		
        msca.Address.initialize();
        msca.Order.initialize();
	}
	
    msca.controller = function () {
        var self = this;
        switch (self.sendData.action) {
            case 'address/add':
                msca.Address.add();
                break;
            case 'address/edit':
                msca.Address.edit();
                break;
            case 'address/save':
                msca.Address.save();
                break;
            case 'address/remove':
                msca.Address.remove();
                break;
            default:
                return;
        }
    };
	
	msca.Address = {
		callbacks: {
            add: mscaConfig.callbacksTemplate(),
            edit: mscaConfig.callbacksTemplate(),
            save: mscaConfig.callbacksTemplate(),
            remove: mscaConfig.callbacksTemplate()
        },
        setup: function () {
            msca.Address.wrapper = '#mscaAddress';
            msca.Address.prefix = '#msca_addr_';
            msca.Address.form = '#mscaForm';
        },
        initialize: function () {
            msca.Address.setup();
            if (!$(msca.Address.wrapper).length) {
                return;
            }
        },
        add: function () {
            var callbacks = msca.Address.callbacks;
            callbacks.add.response.success = function (response) {
				$(msca.Address.form).remove();
				$(msca.Address.wrapper).append( response.data.html );
            };
            msca.send(msca.sendData.formData, msca.Address.callbacks.add);
        },
		edit: function () {
            var callbacks = msca.Address.callbacks;
            callbacks.edit.response.success = function (response) {
				$(msca.Address.form).remove();
				$(msca.Address.wrapper).append( response.data.html );
            };
            msca.send(msca.sendData.formData, msca.Address.callbacks.edit);
        },
		save: function () {
            var callbacks = msca.Address.callbacks;
            callbacks.save.response.error = function (response) {
				$.each(response.data, function(i,message){
					miniShop2.Message.error(message);
				});
            };
            callbacks.save.response.success = function (response) {
				$(msca.Address.form).remove();
				$(msca.Address.wrapper).html( $(response.data.html).html() );
            };
            msca.send(msca.sendData.formData, msca.Address.callbacks.save);
        },
		remove: function () {
            var callbacks = msca.Address.callbacks;
            callbacks.remove.response.success = function (response) {
				$(msca.Address.wrapper).html( $(response.data.html).html() );
            };
            msca.send(msca.sendData.formData, msca.Address.callbacks.remove);
        },
	}
	
	msca.Order = {
		callbacks: {
            values: mscaConfig.callbacksTemplate(),
        },
        setup: function () {
            msca.Order.select = '#msCustomerAddressForm';
        },
        initialize: function () {
            if (!$(miniShop2.Order.order).length) {
                return;
            }
            msca.Order.setup();
			miniShop2.$doc
                    .on('change', miniShop2.Order.order + ' select', function () {
                        var $this = $(this);
                        var key = $this.attr('name');
                        var value = $this.val();
                        miniShop2.Order.add(key, value);
                    });
					
			miniShop2.Callbacks.add('Order.add.response.success', 'msca', function(response) {
				if (response.data.msca_address){
					msca.Order.values();
				}
			});
        },
        values: function () {
            var callbacks = msca.Order.callbacks;
            callbacks.values.response.success = function (response) {
				$.each(response.data, function(i,v){
					var field = $('[name="' + i + '"]', miniShop2.Order.order);
					if (field.is('select')){
						if (field.children('option[value="' + v + '"]:enabled').length > 0){
							field.children('option[value="' + v + '"]:enabled').attr('selected',true).prop('selected',true);
							field.trigger('change');
						}
					}
					else if (field.is('[type="radio"],[type="checkbox"]')){
						if (field.filter('[value="' + v + '"]:enabled:not(:checked)').length > 0) {
							field.filter('[value="' + v + '"]:enabled').trigger('click');
						}
					}
					else{
						field.filter(':enabled').val( v ).trigger('change');
					}
				});
            };
			var data = {};
            data[msca.actionName] = 'order/values';
            msca.send(data, msca.Order.callbacks.values);
        },
	}
	
    $(document).ready(function ($) {
        msca.initialize();
    });

    window.msca = msca;
})(window, document, jQuery, mscaConfig);