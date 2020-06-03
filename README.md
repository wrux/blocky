# Blocky plugin for Craft CMS 3.x

Utility plugin for Craft CMS to map Matrix fields.

Blocky handles the logic of parsing your Matrix blocks so you can create cleaner Twig templates.

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

    ```bash
    cd /path/to/project
    ```

2. Then tell Composer to load the plugin:

    ```bash
    composer require wrux/blocky
    ```

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Blocky.

## Block Parser Overview

## Configuring Block Parser

1. Create `config/blocks.php` inside your Craft project:

    ```php
    <?php

    return [
      'textBlock' => 'app\blocks\TextBlock',
    ];
    ```

2. Somewhere in your project, create block classes for each Matrix block which extends `wrux\blocky\Block`

    Here's an example block:

    ```php
    <?php

    namespace app\blocks;

    use wrux\blocky\Block;

    class TextBlock extends Block {

      public string $template = 'text.twig';

      public function getContext(): array {
        return [
          'text' => !empty($this->block->contentHtml)
            ? $this->block->contentHtml->getParsedContent()
            : NULL,
        ];
      }
    }
    ```

## Using Blocky

Blocky is available at `craft.blocky` in the template.

### Parsing Blocks
```twig
{% set blocks = craft.blocky.parseBlocks(entry.blockComponents) %}
```

### Example

```twig
{% if blocks.hasBlocks %}
  <div class="blocks">
    {% for block in blocks %}
      <section class="block {{ 'block--' ~ block.type }}">
        {% include block.template ignore missing with block.context only %}
      </section>
    {% endfor %}
  </div>
{% endif %}
```


## Block Parser Roadmap

Some things to do, and ideas for potential features:

* Testing 🔥
* Create a custom Twig function

Brought to you by [Callum Bonnyman](https://bloke.blog)
