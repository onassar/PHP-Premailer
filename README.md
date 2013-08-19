PHP-Premailer
=============

### Quick Example

``` php
<?php

    // Source
    require_once APP . '/vendors/PHP-Premailer/Premailer.class.php';

    // Pass in the html markup (which will have style blocks in it)
    $premailer = (new Premailer($body));    
    $body = $premailer->getConvertedHtml();
    
```

### What is the library useful for?

This library is useful for converting your email templates (which use `style` blocks for defining the UI) into sendable markup (with inline styles inserted in the right places, based on those `style` blocks).

### What does this library do?

It goes through any `style` blocks, and searches your markup/html for those elements. It then inserts those styles inline, to make sure it's renderable by the highest number of email clients.

### The backstory

While developing some emails for my recent project, [Podium](http://hellopodium.com/), I stumbled on [alexdunae](https://github.com/alexdunae)'s wonderful tool [Premailer](http://premailer.dialect.ca/). This tool allows you copy/paste markup with `style` tags, and have inline-styled code returned back.

I looked around for some libraries to do that programatically in PHP, finding two. [CssToInlineStyles](https://github.com/tijsverkoyen/CssToInlineStyles) and [InlineStyle](https://github.com/christiaan/InlineStyle). Neither of these worked for my situation, so I decided to provide a PHP proxy/wrapper for [alexdunae](https://github.com/alexdunae/premailer)'s open source version (accessible here: <https://github.com/alexdunae/premailer>).

Here it is.  
I hope you find it useful.

### Example Walkthrough

``` php
<?php

    // Source
    require_once APP . '/vendors/PHP-Premailer/Premailer.class.php';

    // Pass in the html markup (which will have style blocks in it)
    $premailer = (new Premailer($body));    
    $body = $premailer->getConvertedHtml();
    
```

That's it.  
The library works by generating `bash` code to call the `ruby` script. That script then uses the open source `premailer` gem to make the conversions. Here is an example of before/after:

**Before:**

``` html
<style>
	body {
		padding: 0px;
		color: orange;
	}
	.Oliver {
		border: 1px solid red;
	}
	body .Oliver {
		font-size: 14px;
		line-height: 20px;
	}
</style>
   <body><div class="Oliver">Nassar</div></body>
```

**After (raw):**

``` html
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd"><html><head></head><body style="padding: 0px; color: orange;"><div style="border: 1px solid red; line-height: 20px; font-size: 14px;">Nassar</div></body></html>
```

**After (formatted):**

``` html
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<head>
</head>
	<body style="padding: 0px; color: orange;">
		<div style="border: 1px solid red; line-height: 20px; font-size: 14px;">Nassar</div>
	</body>
</html>
```

See <https://github.com/onassar/PHP-Premailer/blob/master/Premailer.class.php#L22> for the options the PHP library supports (almost all of them).

If you have any feedback, please reach out: <onassar@gmail.com>

#### Headups
I noticed a strange bug. While using the open source ruby gem on a VM on my OSX machine, everything went smoothly with the `remove_classes` and `remove_ids` options. They worked as expected (eg. the styles would be applied, and the classes or ids would be removed after the styles had been applied).

On a different server, however, there was some inconsitant behaviour. Specifically, if these options were set to `true`, some styles would or wouldn't get applied.

I'm not sure if the ids/classes were getting removed before the conversion was being done, or what. There was a gem I thought was a prerequisite, called `hpricot`, that I installed on the latter server, that the former didn't have.

I'm not sure if that's related, but a heads up if you notice that behaviour.