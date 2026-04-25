<!DOCTYPE html>
<html style="height:100%; margin:0; padding:0;">
<head>
    <title>Gallery Embed</title>
    <script src="https://unpkg.com/@ruffle-rs/ruffle"></script>
    <script src="javascript/swfobject_source.js"></script>
    <style>
        body { margin:0; padding:0; height:100%; background:#000; overflow:hidden; }
        #gallery { width:100%; height:100%; }
        /* This ensures the Ruffle player fills the iframe correctly */
        ruffle-player { width: 100%; height: 100%; }
    </style>
    
    <script>
        // Force the Ruffle Splash Screen and Unmute behavior
        window.RufflePlayer = window.RufflePlayer || {};
        window.RufflePlayer.config = {
            "autoplay": "on",        // Attempts to play immediately
            "unmuteOverlay": "hidden", // Shows the big "Play" button splash screen
            "letterbox": "on",
            "warnOnUnsupportedContent": false
        };
    </script>
</head>
<body>
    <div id="gallery">Loading Gallery...</div>
    <script type="text/javascript">
        var so = new SWFObject("imagevue.swf", "imagevue", "100%", "100%", "8", "#000000");
        so.addVariable("configfile", "config.ini");
        so.addVariable("serverextension", "php");
        // Ensure the audio engine knows to start
        so.addVariable("audioinit", "true"); 
        so.write("gallery");
    </script>
</body>
</html>