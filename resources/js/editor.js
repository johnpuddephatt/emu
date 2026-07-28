import domReady from '@wordpress/dom-ready';

domReady(() => {
  wp.blocks.unregisterBlockStyle('core/button', 'outline');

  wp.blocks.registerBlockStyle('core/paragraph', {
    name: 'subtitle',
    label: 'Subtitle',
  });

  wp.blocks.registerBlockStyle('core/list', {
    name: 'checklist',
    label: 'Checklist',
  });

  wp.blocks.registerBlockStyle('core/list-item', {
    name: 'checked',
    label: 'Checked',
  });

  wp.blocks.registerBlockStyle('core/button', {
    name: 'inverted',
    label: 'Inverted',
  });

  wp.blocks.registerBlockStyle('core/button', {
    name: 'primary',
    label: 'Primary',
  });

  wp.blocks.registerBlockStyle('core/group', {
    name: 'lined',
    label: 'Lined',
  });
});
