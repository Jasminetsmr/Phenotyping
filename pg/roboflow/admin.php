<form id="inputForm">
    <div class="header_robo">
        <div class="header__grid">
            <img class="header__logo" src="logo.png" alt="Roboflow Inference">
            <div>
                <label class="header__label" for="model">Model</label>
                <input class="input" type="text" id="model" />
            </div>
            <div>
                <label class="header__label" for="version">Version</label>
                <input class="input" type="number" id="version" />
            </div>
            <div>
                <label class="header__label" for="api_key">API Key</label>
                <input class="input" type="text" id="api_key" />
            </div>
        </div>
    </div>

    <div class="content_robo">
        <div class="content__grid">
            <div class="col-12-s6-m4" id="method">
                <label class="input__label">Upload Method</label>
                <div>
                    <button data-value="upload" id="computerButton" class="bttn left fill active">Upload</button>
                    <button data-value="url" id="urlButton" class="bttn right fill">URL</button>
                </div>
            </div>

            <div class="col-12-m8" id="fileSelectionContainer">
                <label class="input__label" for="file">Select File</label>
                <div class="flex">
                    <input class="input input--left flex-1" type="text" id="fileName" disabled />
                    <button id="fileMock" class="bttn right active">Browse</button>
                </div>
                <input style="display: none;" type="file" id="file" />
            </div>

            <div class="col-12-m8" id="urlContainer">
                <label class="input__label" for="file">Enter Image URL</label>
                <div class="flex">
                    <input type="text" id="url" placeholder="https://path.to/your.jpg" class="input"/><br>
                </div>
            </div>

            <div class="col-12-m6">
                <label class="input__label" for="classes">Filter Classes</label>
                <input type="text" id="classes" placeholder="Enter class names" class="input"/><br>
                <span class="text--small">Separate names with commas</span>
            </div>

            <div class="col-6-m3 relative">
                <label class="input__label" for="confidence">Min Confidence</label>
                <div>
                    <icon_robo class="fas fa-crown"></icon_robo>
                    <span class="icon">%</span>
                    <input type="number" id="confidence" value="40" max="100" accuracy="2" min="0" class="input input__icon"/></div>
                </div>
            <div class="col-6-m3 relative">
                <label class="input__label" for="overlap">Max Overlap</label>
                <div>
                    <icon_robo class="fas fa-object-ungroup"></icon_robo>
                    <span class="icon">%</span>
                    <input type="number" id="overlap" value="30" max="100" accuracy="2" min="0" class="input input__icon"/></div>
            </div>
            <div class="col-6-m3" id="format">
                <label class="input__label">Inference Result</label>
                <div>
                    <button id="imageButton" data-value="image" class="bttn left fill active">Image</button>
                    <button id="jsonButton" data-value="json" class="bttn right fill">JSON</button>
                </div>
            </div>
            <div class="col-12 content__grid" id="imageOptions">
                <div class="col-12-s6-m4" id="labels">
                    <label class="input__label">Labels</label>
                    <div>
                        <button class="bttn left">Off</button>
                        <button data-value="on" class="bttn right active">On</button>
                    </div>
                </div>
                <div class="col-12-s6-m4" id="stroke">
                    <label class="input__label">Stroke Width</label>
                    <div>
                        <button data-value="1" class="bttn left">1px</button>
                        <button data-value="2" class="bttn active">2px</button>
                        <button data-value="5" class="bttn">5px</button>
                        <button data-value="10" class="bttn right">10px</button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" value="Run Inference" class="bttn__primary">Run Inference</button>
            </div>
        </div>
        <div class="result" id="resultContainer">
            <div class="divider"></div>
            <div class="result__header">
                <h3 class="headline">Result</h3>
                <a href="#">Copy Code</a>
            </div>
            <pre id="output" class="codeblock"/> here is your json </pre>
        </div>
    </div>
</form>

<script type="text/javascript">
$(function() {
	//values pulled from query string
	$('#model').val("cabai-persemaian");
	$('#version').val("2");
	$('#api_key').val("JvgLsS6YqDzpwF9jmXtR");

	setupButtonListeners();
});

var infer = function() {
	$('#output').html("Inferring...");
	$("#resultContainer").show();
	$('html').scrollTop(100000);

	getSettingsFromForm(function(settings) {
		settings.error = function(xhr) {
			$('#output').html("").append([
				"Error loading response.",
				"",
				"Check your API key, model, version,",
				"and other parameters",
				"then try again."
			].join("\n"));
		};

		$.ajax(settings).then(function(response) {
			if(settings.format == "json") {
				var pretty = $('<pre>');
				var formatted = JSON.stringify(response, null, 4)

				pretty.html(formatted);
				$('#output').html("").append(pretty);
				$('html').scrollTop(100000);
			} else {
				var arrayBufferView = new Uint8Array(response);
				var blob = new Blob([arrayBufferView], {
					'type': 'image\/jpeg'
				});
				var base64image = window.URL.createObjectURL(blob);

				var img = $('<img/>');
				img.get(0).onload = function() {
					$('html').scrollTop(100000);
				};
				img.attr('src', base64image);
				$('#output').html("").append(img);
			}
		});
	});
};

var retrieveDefaultValuesFromLocalStorage = function() {
	try {
		var api_key = localStorage.getItem("rf.api_key");
		var model = localStorage.getItem("rf.model");
		var format = localStorage.getItem("rf.format");

		if (api_key) $('#api_key').val(api_key);
		if (model) $('#model').val(model);
		if (format) $('#format').val(format);
	} catch (e) {
		// localStorage disabled
	}

	$('#model').change(function() {
		localStorage.setItem('rf.model', $(this).val());
	});

	$('#api_key').change(function() {
		localStorage.setItem('rf.api_key', $(this).val());
	});

	$('#format').change(function() {
		localStorage.setItem('rf.format', $(this).val());
	});
};

var setupButtonListeners = function() {
	// run inference when the form is submitted
	$('#inputForm').submit(function() {
		infer();
		return false;
	});

	// make the buttons blue when clicked
	// and show the proper "Select file" or "Enter url" state
	$('.bttn').click(function() {
		$(this).parent().find('.bttn').removeClass('active');
		$(this).addClass('active');

		if($('#computerButton').hasClass('active')) {
			$('#fileSelectionContainer').show();
			$('#urlContainer').hide();
		} else {
			$('#fileSelectionContainer').hide();
			$('#urlContainer').show();
		}

		if($('#jsonButton').hasClass('active')) {
			$('#imageOptions').hide();
		} else {
			$('#imageOptions').show();
		}

		return false;
	});

	// wire styled button to hidden file input
	$('#fileMock').click(function() {
		$('#file').click();
	});

	// grab the filename when a file is selected
	$("#file").change(function() {
		var path = $(this).val().replace(/\\/g, "/");
		var parts = path.split("/");
		var filename = parts.pop();
		$('#fileName').val(filename);
	});
};

var getSettingsFromForm = function(cb) {
	var settings = {
		method: "POST",
	};

	var parts = [
		"https://detect.roboflow.com/",
		$('#model').val(),
		"/",
		$('#version').val(),
		"?api_key=" + $('#api_key').val()
	];

	var classes = $('#classes').val();
	if(classes) parts.push("&classes=" + classes);

	var confidence = $('#confidence').val();
	if(confidence) parts.push("&confidence=" + confidence);

	var overlap = $('#overlap').val();
	if(overlap) parts.push("&overlap=" + overlap);

	var format = $('#format .active').attr('data-value');
	parts.push("&format=" + format);
	settings.format = format;

	if(format == "image") {
		var labels = $('#labels .active').attr('data-value');
		if(labels) parts.push("&labels=on");

		var stroke = $('#stroke .active').attr('data-value');
		if(stroke) parts.push("&stroke=" + stroke);

		settings.xhr = function() {
			var override = new XMLHttpRequest();
			override.responseType = 'arraybuffer';
			return override;
		}
	}

	var method = $('#method .active').attr('data-value');
	if(method == "upload") {
		var file = $('#file').get(0).files && $('#file').get(0).files.item(0);
		if(!file) return alert("Please select a file.");

		getBase64fromFile(file).then(function(base64image) {
			settings.url = parts.join("");
			settings.data = base64image;

			console.log(settings);
			cb(settings);
		});
	} else {
		var url = $('#url').val();
		if(!url) return alert("Please enter an image URL");

		parts.push("&image=" + encodeURIComponent(url));

		settings.url = parts.join("");
		console.log(settings);
		cb(settings);
	}
};

var getBase64fromFile = function(file) {
	return new Promise(function(resolve, reject) {
		var reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = function() {
		resizeImage(reader.result).then(function(resizedImage){
			resolve(resizedImage);
		});
    };
		reader.onerror = function(error) {
			reject(error);
		};
	});
};


var resizeImage = function(base64Str) {

	return new Promise(function(resolve, reject) {
		var img = new Image();
		img.src = base64Str;
		img.onload = function(){
			var canvas = document.createElement("canvas");
			var MAX_WIDTH = 1500;
			var MAX_HEIGHT = 1500;
			var width = img.width;
			var height = img.height;
			if (width > height) {
				if (width > MAX_WIDTH) {
					height *= MAX_WIDTH / width;
					width = MAX_WIDTH;
				}
			} else {
				if (height > MAX_HEIGHT) {
					width *= MAX_HEIGHT / height;
					height = MAX_HEIGHT;
				}
			}
			canvas.width = width;
			canvas.height = height;
			var ctx = canvas.getContext('2d');
			ctx.drawImage(img, 0, 0, width, height);
			resolve(canvas.toDataURL('image/jpeg', 1.0));  
		};
    
	});	
};

</script>