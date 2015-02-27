# RecentlyViewed

Show recently viewed products.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/RecentlyViewed``` directory and be sure that the name of the module is RecentlyViewed.
* Activate it in your thelia administration panel

### Composer

```
$ composer require thelia/recently-viewed-module:~1.0
```

## Usage

To use recently viewed products you can use Smarty function or Loop

You can modify maximum value in RecentlyViewed.php (default value : 8)

## Smarty

exemple :

```
{get_recently_viewed productId=$product_id}
```

result : "2,18,19,65"

## Loop

recentlyviewed

this loop extends default Product Loop.

**@see** : http://doc.thelia.net/en/documentation/loop/product.html


### Input arguments

|Argument |Description |
|---      |--- |
|**current_product** | current product id doesn't show in loop results|


### Exemple
```
{loop type="recentlyviewed" name="recentlyviewed_loop" current_product=$product_id limit="4"}
    {$TITLE} ({$REF})
{/loop}
```

