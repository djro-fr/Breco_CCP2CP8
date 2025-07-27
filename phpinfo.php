<?php 

if (extension_loaded("mongodb")) {
    echo "✅ Extension mongodb chargée.";
} else {
    echo "❌ Extension mongodb NON chargée.";
}

phpinfo(); 
?>