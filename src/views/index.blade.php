<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With');
?>
<html>
<head>
    <link rel="icon" type="image/png" href="/vendor/swaggervel/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/vendor/swaggervel/images/favicon-16x16.png" sizes="16x16" />
    <link href='/vendor/swaggervel/css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='/vendor/swaggervel/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='/vendor/swaggervel/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='/vendor/swaggervel/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
    <link href='/vendor/swaggervel/css/print.css' media='print' rel='stylesheet' type='text/css'/>
    <script src='/vendor/swaggervel/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/handlebars-2.0.0.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/underscore-min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/backbone-min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/swagger-ui.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/highlight.7.3.pack.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/marked.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/swagger-oauth.js' type='text/javascript'></script>

    <!-- Some basic translations -->
    <!-- <script src='/vendor/swaggervel/lang/translator.js' type='text/javascript'></script> -->
    <!-- <script src='/vendor/swaggervel/lang/ru.js' type='text/javascript'></script> -->
    <!-- <script src='/vendor/swaggervel/lang/en.js' type='text/javascript'></script> -->

    <script type="text/javascript">
    $(function () {
      // Pre load translate...
      if(window.SwaggerTranslator) {
        window.SwaggerTranslator.translate();
      }
      window.swaggerUi = new SwaggerUi({
        url: "{!! $urlToDocs !!}",
        dom_id: "swagger-ui-container",
        supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
        onComplete: function(swaggerApi, swaggerUi){
          @if(isset($requestHeaders))
            @foreach($requestHeaders as $requestKey => $requestValue)
              window.authorizations.add("{!!$requestKey!!}", new ApiKeyAuthorization("{!!$requestKey!!}", "{!!$requestValue!!}", "header"))  ;
            @endforeach
          @endif
          if(typeof initOAuth == "function") {
            initOAuth({
              clientId: "your-client-id",
              clientSecret: "your-client-secret-if-required",
              realm: "your-realms",
              appName: "your-app-name",
              scopeSeparator: ",",
              additionalQueryStringParams: {}
            });
          }

          if(window.SwaggerTranslator) {
            window.SwaggerTranslator.translate();
          }

          $('pre code').each(function(i, e) {
            hljs.highlightBlock(e)
          });

          addApiKeyAuthorization();
        },
        onFailure: function(data) {
          log("Unable to Load SwaggerUI");
        },
        docExpansion: "none",
        apisSorter: "alpha",
        defaultModelRendering: 'schema',
        showRequestHeaders: false
      });

      function addApiKeyAuthorization(){
        var key = encodeURIComponent($('#input_apiKey')[0].value);

        if(key && key.trim() != "") {
            var apiKeyAuth = new SwaggerClient.ApiKeyAuthorization("api_key", key, "query");
            window.swaggerUi.api.clientAuthorizations.add("api_key", apiKeyAuth);
            log("added key " + key);
        }
      }

      $('#input_apiKey').change(addApiKeyAuthorization);

      // if you have an apiKey you would like to pre-populate on the page for demonstration purposes...
      var apiKey = "{!! Config::get('swaggervel.api-key') !!}";
      $('#input_apiKey').val(apiKey);

      window.swaggerUi.load();

      function log() {
        if ('console' in window) {
          console.log.apply(console, arguments);
        }
      }
  });
  </script>
</head>

<body class="swagger-section">
<div id='header'>
  <div class="swagger-ui-wrap">
    <a id="logo" href="http://swagger.io">swagger</a>
    <form id='api_selector'>
      <div class='input'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl" type="text"/></div>
      <div class='input'><input placeholder="api_key" id="input_apiKey" name="apiKey" type="text"/></div>
      <div class='input'><a id="explore" href="#" data-sw-translate>Explore</a></div>
    </form>
  </div>
</div>

<div id="message-bar" class="swagger-ui-wrap" data-sw-translate>&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
