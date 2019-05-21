---
layout: default
title: Fluid Typography
nav_order: 6
---

# Fluid/Responsive Typography


## What "fluid typography" is
Fluid Typography allows your site's font-size to change depending on the viewport size of your visitor's device.

## How can you control it?

The Gridd Theme features a "Fluid Typography Ratio" slider in the theme's customizer, allowing you to set a ratio which is used to calculate the font-size based on the viewport width of your visitors's device. You can find that slider under the "Typography" section in your customizer.

## How is the font-size calculated?

The resulting font-size is a mix of the defined font-size, the defined ratio and the viewport size.

You don't need to sit down with a pen and paper doing all the math, we do all that for you. All you need to do is play with the slider until you find a value that satisfies you. Our personal experience is that a value from `0.3` to `0.6` gives the best results if all container maximum-widths etc are using values in `em`.

The font-size is then calculated using the following CSS:

```css
font-size: calc(var(--gridd-font-size) + var(--gridd-responsive-typo-ratio) * 1vw);
```

In the above CSS, `var(--gridd-font-size)` is the font-size you have defined, and `var(--gridd-responsive-typo-ratio)` is the defined ratio.


`1vw` is defined in CSS as 1/100th of the screen-width, so if you set ratio to `1`, then for every 100px of screen-width your users will gain 1px for the font-size. The slider allows for extreme fine-tuning of the ratio value, so you can define a value of `0.25` for example. This will add 1px of font-size for every 400px of screen-width.


If for example you define the base font-size as `16px`, and the ratio is set to `0.3`, then a user on a 1920px wide screen will get a font-size of `16px + 0.3 * 1920px/100` which is `21.76px`. A user on a `1024px` wide screen will see a `19.072px` font-size and so on.
