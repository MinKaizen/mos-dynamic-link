# Description

Provides a shortcode for generating a dynamic link based on URL parameters.

# Quick start

Add this shortcode to your page:
> [mos_dynamic_link link_template=”www.google.com/search?q=**%%query%%**” link_text="Search Google"]

Now visit this link:

> yoursite.com/?**query**=hello world

Output:

<a href="https://www.google.com/search?q=hello+world" target="_blank" rel="noopener noreferrer">Search Google</a>

HTML output:

```html
<a href="https://www.google.com/search?q=hello+world" target="_blank" rel="noopener noreferrer">Search Google</a>
```

> NOTE: You can read any number of URL parameters, as long as you include them like this: `%%url_param_name%%` in the link template. To read a URL parameter called `email`, simply use `%%email%%` in your link template.

# Defaults

The only 2 mandatory attributes is `link_template`. By default, the shortcode will:

- Wrap the url in an `<a>` element
- Prepend `https://` if no protocol is provided in link
- Set the link text to "CLICK HERE"
- Set the link to open in a new tab (including noopener and noreferrer)
- Encode URL Parameter values

# Options

## link_template (required)

The link template. Use %%param_name%% as merge tags. `%%var_name%%` means read the URL parameter called `var_name`

## link_text (default="CLICK HERE")

The link text to display. No effect if `wrap_html` is `false`

## wrap_html (default=true)

Whether or not to wrap the link in an `<a>` element. If false, the url will be printed as plain text


## encode (default=true)

Whether or not to url encode the value of query parameters.

e.g.`hello world` becomes `hello+world`

## class (default='')

Class name to add to the `<a>` element. No effect if `wrap_html` is `false`

Multiple classnames can be added by passing them in a single string and separating them with spaces.

## new_tab (default=true)

Whether or not to set the link as "open in new tab". No effect if `wrap_html` is `false`