postmap
=======

This Wordpress plugin allows you to add a google map in your wordpress post.  The map can be automatically shown based
on whether or not a location has been associated with the post.  Also included in a tool for manually selecting locations
on a large map.

shortcodes
----------

### pagemap_shortcode
Displays a map with a marker at the appropriate location.  A location is identified within the current page or post by
the meta property **location**.  It should be provided with any of the following formats:

+/-123.44566, +/-123.423434
+/-123.44566 +/-123.423434

You can provide the following attributes as well:
**width**
**height**
**zoom**

### postmap_shortcode
Displays a map which adds a marker for every published post which has the **location** meta property defined.  You can provide
the following attributes:
**width**
**height**
**zoom**

