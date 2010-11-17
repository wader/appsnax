ipamanifest
===========

Generate wireless app distribution mainifests directly from IPA files.

Makes it possible for testers to install and upgrade applications directly
from Safari on iOS devices without involving iTunes. Wireless app
distribution requires iOS 4.0 or higher. Also ipamanifest assumes that the IPA
files has embedded provision files.

Default you should put your IPA files into the `ipas` directory.

Notable features:

*   UIPrerenderedIcon aware, skips shine effect on web page and while installing.
*   App icon rounding and shadow effect in CSS.
*   Scaled images, tries to use @2x app icon first.
*   Does not require a temp directory or write permissions on the web server.
*   Two example themes.

Example usage
-------------

![iwebkit example image](/wader/ipamanifest/raw/master/iwebkitexample.png)
![springboard example image](/wader/ipamanifest/raw/master/springboardexample.png)
