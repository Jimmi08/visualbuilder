/**
 * Class for work with ajax methods
 *  
 * @version 0.0.1
 * @class  Qoobe107Driver
 */
//module.exports.Qoobe107Driver = Qoobe107Driver;
function Qoobe107Driver(options) {
    this.options = options || {};
    this.pages = this.options.pages || null;
    this.page = this.options.page || '';
    //    this.assets = [{"type":"js","name":"media-models", "src":"/wp-includes/js/media-models.js"}];
}

/**
 * Get url iframe
 * 
 * @returns {String}
 */
Qoobe107Driver.prototype.getFrontendPageUrl = function() {
    return this.options.frontendPageUrl;
};

/**
 * Get url iframe
 * 
 * @returns {String}
 */
Qoobe107Driver.prototype.getIframePageUrl = function() {
    return this.options.iframeUrl;
};

/**
 * Go to the admin view of the edited page
 * 
 * @returns {String}
 */
Qoobe107Driver.prototype.exit = function() {
    window.location.href = 'admin_config.php?mode=main&action=list';
};
 ;
 
/**
 * Save page data
 * @param {text} func
 * @param {integer} pageId
 * @param {Array} data DOMElements and JSON
 * @param {savePageDataCallback} cb - A callback to run.
 */
Qoobe107Driver.prototype.savePageData = function(data, cb) {
    var dataToSend = JSON.stringify({
        func : 'qoob_save_page_data',
        pageId: this.options.pageId,
        data: data     
    });
 
    jQuery.ajax({
        url: this.options.ajaxUrl,
        type: 'POST',
        data: dataToSend,
        processData: false,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',  
        beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},        
        success: function(response) {
            if (response.success) {
                cb(null, response.success);
            } else {
                cb(response.error, response.success);
                if (response.error) {
                    console.error(response.error);
                } else {
                    console.error("Error in 'Qoobe107Driver.savePageData'. Sent data from server failed.");
                }
            }
        },
        error: function(xrh, error) {  
            console.error(error);     
        }      
    });
       
};

 
 
/**
 * Get page data
 * 
 * @param {integer} pageId
 * @param {loadPageDataCallback} cb - A callback to run.
 */
Qoobe107Driver.prototype.loadPageData = function(cb) {
    //console.log(this.options.pageId);
    jQuery.ajax({
        url: this.options.ajaxUrl,
        type: 'POST',
        data: {
            action: 'qoob_load_page_data',
            page_id: this.options.pageId,
            lang: 'en'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data) {
                //console.log(response.data);
                cb(null, response.data);
            } else {
                console.error("Error in 'Qoobe107Driver.loadPageData'. Returned data from server is fail.");
                if (response.error) {
                    console.error(response.error);
                }
            }
        },
        error: function(xrh, error) {
            console.error(error);
        }
    });
};

/**
 * Get qoob libs
 * 
 * @param {loadQoobDataCallback} cb - A callback to run.
 */
Qoobe107Driver.prototype.loadLibrariesData = function(cb) {
    jQuery.ajax({
        url: this.options.ajaxUrl,
        type: 'POST',
        data: {
            action: 'qoob_load_libraries_data'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.libs) {
                //console.log(response.libs);
                cb(null, response.libs);
            } else {
                console.error("Error in 'Qoobe107Driver.loadLibrariesData'. Returned data from server is fail.");
                if (response.error) {
                    console.error(response.error);
                }
            }
        },
        error: function(xmlHttpRequest, textStatus) {
            console.log(xmlHttpRequest.responseText);  console.log(textStatus)
            if (xmlHttpRequest.readyState == 0 || xmlHttpRequest.status == 0) {
                return; // it's not really an error
            } else {
                // Do normal error handling
                console.error(textStatus);
            }
        }
    });
};
/**
 * Save page template
 * 
 * @param {savePageTemplateCallback} cb - A callback to run.
 */
Qoobe107Driver.prototype.savePageTemplate = function(data, cb) {
    jQuery.ajax({
        url: this.options.ajaxUrl + '?action=qoob_save_page_template',
        type: 'POST',
        data: JSON.stringify(data),
        processData: false,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
        success: function(response) {
            cb(null, response.success);
        }
    });
};

/**
 * Load page templates
 * 
 * @param {loadPageTemplatesCallback} cb - A callback to run.
 */
Qoobe107Driver.prototype.loadPageTemplates = function(cb) {
    jQuery.ajax({
        dataType: "json",
        url: this.options.ajaxUrl,
        type: 'POST',
        data: {
            action: 'qoob_load_page_templates'
        },
        error: function(jqXHR, textStatus) {
            console.error("Error in 'Qoobe107Driver.loadPageTemplates'. Returned data from server is fail.");
            cb(textStatus);
        },
        success: function(response) {
            if (response.success && response.templates) {
                cb(null, JSON.parse(response.templates));
            }
        }
    });
};

/**
 * Load translations
 * 
 * @param {loadTranslationsCallback} cb - A callback to run.
 */
Qoobe107Driver.prototype.loadTranslations = function(cb) {
    jQuery.ajax({
        dataType: "json",
        url: this.translationsUrl,
        error: function(jqXHR, textStatus) {
            cb(textStatus);
        },
        success: function(data) {
            cb(null, data);
        }
    });
};

/**
 * Load main menu
 * @param {Array} menu
 * @returns {Array}
 */
Qoobe107Driver.prototype.mainMenu = function(menu) {
    var self = this;
    //console.log(self.getFrontendPageUrl());
    // console.log(this.options.frontendPageUrl);
    //console.log(self.getIframePageUrl());
    //console.log(this.options.iframeUrl);
    menu.push({
        "id": "save-template",
        "label": {"save_as_template": "Save as template"},
        "action": "",
        "icon": ""
    }, {
        "id": "show-frontend",
        "label": {"showOnFrontend": "Show on frontend"},
        "action": function() {
            window.open(self.getFrontendPageUrl(), '_blank');
        },
        "icon": ""
    });

    return menu;
};
 

/**
 * Custom field video action
 * @param {Array} actions
 * @returns {Array}
 */
Qoobe107Driver.prototype.fieldVideoActions = function(actions) {
    var self = this;
    var customActions = [{
        "id": "upload",
        "label": {"upload": "Upload"},
        "action": function(videoField) {
            videoField.$el.find('.input-file').remove();
            videoField.$el.append('<input type="file" class="input-file" name="video">');

            videoField.$el.find('.input-file').trigger('click');

            videoField.$el.find('.input-file').change(function() {
                var parent = jQuery(this),
                    container = videoField.$el.find('.field-video-container'),
                    file = jQuery(this).val();

                if (container.hasClass('empty') || container.hasClass('upload-error')) {
                    container.removeClass('empty upload-error upload-error__size upload-error__format');
                }

                // 30 MB limit
                if (jQuery(this).prop('files')[0].size > 31457280) {
                    container.addClass('upload-error upload-error__size');
                } else {
                    if (file.match(/.(mp4|ogv|webm)$/i)) {
                        var formData = new FormData();
                        formData.append('video', jQuery(this)[0].files[0], jQuery(this)[0].files[0].name);
                        self.upload(formData, function(error, url) {
                            if ('' !== url) {
                                var src = { 'url': url, preview: '' };
                                videoField.changeVideo(src);
                                parent.val('');

                                if (!container.hasClass('empty-preview')) {
                                    container.addClass('empty-preview');
                                }
                            }
                        });
                    } else {
                        container.addClass('upload-error upload-error__format');
                    }
                }
            });
        },
        "icon": ""
    }, {
        "id": "reset",
        "label": {"resetToDefault": "Reset to default"},
        "action": function(videoField) {
            var container = videoField.$el.find('.field-video-container');

            videoField.changeVideo(videoField.options.defaults);
            if (container.hasClass('empty') ||
                container.hasClass('empty-preview') ||
                container.hasClass('upload-error')) {
                container.removeClass('empty empty-preview upload-error');
            }
        },
        "icon": ""
    }];

    var glueActions = actions.concat(customActions);

    return glueActions;
};

 

/**
 * Upload video
 * @param {Array} dataFile
 * @param {uploadCallback} cb - A callback to run.
 */
Qoobe107Driver.prototype.uploadVideo = function(dataFile, cb) {
    var formData = new FormData();
    formData.append('video', dataFile[0], dataFile[0].name);

    this.upload(formData, function(error, url) {
        cb(error, url);
    });
};
 
/**
 * Upload image
 * @param {Array} data
 * @param {uploadCallback} cb - A callback to run.
 */
Qoobe107Driver.prototype.upload = function(data, cb) {
    jQuery.ajax({
        url: this.options.ajaxUrl,
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        error: function(jqXHR, textStatus) {
            cb(textStatus)
            console.error(textStatus);
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                cb(null, data.url);
            } else {
                if (data.error) {
                    console.error(data.message);
                    cb(true);
                }
            }
        }
    });
};

 
/**
 * Custom field image action
 * @param {Array} actions
 * @returns {Array}
 */
Qoobe107Driver.prototype.fieldImageActions = function(actions) {
    var self = this;
    var customActions = [{
        "id": "upload",
        "label": {"upload": "Upload"},
        "action": function(imageField) {
            imageField.$el.find('.input-file').remove();
            imageField.$el.append('<input type="file" class="input-file" name="file_userfile[]">');

            imageField.$el.find('.input-file').trigger('click');

            imageField.$el.find('.input-file').change(function() {
                var file = imageField.$el.find('input[type=file]').val(),
                    container = imageField.$el.find('.field-image-container');

                // 2 MB limit
                if (jQuery(this).prop('files')[0].size > 2097152) {
                    container.addClass('upload-error');
                } else {
                    if (file.match(/.(jpg|jpeg|png|gif)$/i)) {
                        var formData = new FormData();
                        formData.append('file_userfile[]', jQuery(this)[0].files[0], jQuery(this)[0].files[0].name);
                        formData.append("action", "qoob_add_new_image");
                        self.upload(formData, function(error, url) {
                            if ('' !== url) {
                                imageField.changeImage(url);
                                imageField.$el.find('input[type=file]').val('');
                                if (container.hasClass('empty') || container.hasClass('upload-error')) {
                                    container.removeClass('empty upload-error');
                                }
                            }
                        });
                    } else {
                        console.error('file format is not appropriate');
                    }
                }
            });
        },
        "icon": ""
    },  {
        "id": "reset",
        "label": {"resetToDefault": "Reset to default"},
        "action": function(imageField) {
            imageField.changeImage(imageField.options.defaults);

            var container = imageField.$el.find('.field-image-container');

            if ('' === imageField.options.defaults) {
                if (!container.hasClass('empty')) {
                    container.addClass('empty');
                }
            } else {
                container.removeClass('empty upload-error');
            }
        },
        "icon": ""
    }];

    var glueActions = actions.concat(customActions);

    return glueActions;
};

/**
 * Upload image
 * @param {Array} dataFile
 * @param {uploadCallback} cb - A callback to run.
 */
Qoobe107Driver.prototype.uploadImage = function(dataFile, cb) {
    var formData = new FormData();
    formData.append('image', dataFile[0]);
    formData.append("action", "qoob_add_new_image");

    this.upload(formData, function(error, url) {
        cb(error, url);
    });
};