# Blocky Plugin for Craft CMS 3.x

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

## Configuring the Block Parser

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

## Templating

Blocky is available at `craft.blocky` in the template or you can also use the `{% blocks ... %}` Twig tag.

### Twig Tag

The `{% blocks %}` tag works similarly to a Twig for loop. It expects a Matrix field and it will handle the parsing and iteration.

**Example:**

```twig
{% blocks in entry.blockComponents %}
  <section class="block {{ 'block--' ~ type }}">
    {% include template with context only %}
  </section>
{% endblocks %}
```

**Example with skipping empty blocks:**

You can use `skip empty` in the opening tag. This will skip blocks that return an empty context.

```twig
{% blocks in entry.blockComponents skip empty %}
  <section class="block {{ 'block--' ~ type }}">
    {% include template with context only %}
  </section>
{% endblocks %}
```

### Variables

The following variables are available inside the `{% blocks %}` tag.

| Variable        | Value |
| --------------- | ----- |
| block           | The block object. |
| template        | The data returned from the `getTemplate()` method |
| type            | The value returned from the `getType()` method |
| context         | The context returned from the `getContext()` method |
| loop.index      | The current iteration of the loop. (1 indexed) |
| loop.index0     | The current iteration of the loop. (0 indexed) |
| loop.revindex   | The number of iterations from the end of the loop (1 indexed) |
| loop.revindex0  | The number of iterations from the end of the loop (0 indexed) |
| loop.first      | True if first iteration |
| loop.last       | True if last iteration |
| loop.length     | The number of items in the sequence |

### Manually Parsing Blocks

If you don't want to use the Twig tag, blocks can be parsed manually using `craft.blocky` service. Internally this consumes the same `Blocky::$plugin->parseBlocks()` method. This method allows you to check `blocks.hasBlocks` before the for loop.

**Example:**

```twig
{% set blocks = craft.blocky.parseBlocks(entry.blockComponents) %}
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
* Nested blocks with the release of CraftCMS 4.0

Brought to you by [Callum Bonnyman](https://bloke.blog)
