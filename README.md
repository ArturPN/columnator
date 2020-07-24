# Columnator (for Nette)

Simple script that divides photos from a directory into 4 columns, sorted from left to right. One vertical photo is counted as two horizontal ones - this should be styled within your CSS. The aim is to create a pretty equal height of each column.

## Adaptation
1. Put DatabaseModel.php into app/model (and register it in config.neon -> services).
2. Put GalleryPresenter.php into app/modules/YourModule/ or just app/ (depends on your structure).
3. Put gallery.latte into app/modules/YourModule/Gallery or just app/Gallery (depends on your structure).
4. Make sure to check every $dir and $path variable and adapt it into your project. Don't forget to add a CSS.

You don't have to copy all the files - the most important are: functions columnate() and listPhotos() from DatabaseModel.php and for/foreach loop(s) in gallery.latte.
