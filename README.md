ipamanifest
===========

Generate wireless Ad Hoc application distribution mainifests directly from IPA files.

Makes it possible for testers to install and upgrade applications directly
from Safari on iOS devices without involving iTunes. Wireless app
distribution requires iOS 4.0 or higher. Also ipamanifest assumes that the IPA
files has embedded provision files.

Default you should put your IPA files into the `ipas` directory.

Notable features:

*   Warns about minimum iOS version and required device model per IPA based on the device used
    when browsing.
    Also warns if user is using a version too old to support wireless application distribution.
*   If browsing from a non-iOS device it shows iTunes instructions and link to download IPA
    and mobileprovision files per application.
*   Extracts icons and mobileprovision file from IPA on the fly.
*   UIPrerenderedIcon aware, skips shine effect on web page and while installing.
*   App icon rounding, shadow and gloss effect in CSS.
*   Scaled images, tries to use @2x app icon first.
*   Does not require a temp directory or write permissions on the web server.

Example usage
-------------

![iwebkit example image](/wader/ipamanifest/raw/master/iwebkitexample.png)

![springboard example image](/wader/ipamanifest/raw/master/springboardexample.png)

![desktop example image](/wader/ipamanifest/raw/master/desktopexample.png)
