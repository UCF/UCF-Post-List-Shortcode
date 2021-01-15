//
// Initializes a typeahead on the given element.
//

(function ($) {

  $.fn.UCFPostListSearch = function (options) {

    const settings = $.extend({
      localdata: [],
      classnames: {},
      limit: 5,
      templates: {}
    }, options);

    const typeaheadSource = new Bloodhound({
      datumTokenizer(datum) {
        let retval = [];
        for (let i = 0; i < datum.matches.length; i++) {
          retval = retval.concat(Bloodhound.tokenizers.whitespace(datum.matches[i]));
        }
        return retval;
      },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      local: settings.localdata
    });

    this.typeahead(
      {
        hint: false,
        highlight: true,
        minLength: 2,
        classNames: settings.classnames
      },
      {
        source: typeaheadSource,
        limit: settings.limit,
        displayKey(obj) {
          return obj.display;
        },
        templates: settings.templates
      }).on('typeahead:selected', (event, obj) => {
        window.location = obj.link;
      });

    return this;

  };


  $('.ucf-post-search-form').each(function() {
    let $this = $(this);
    let $typeahead = $this.find('.typeahead').first();
    let $args = $(`.post-list-search-settings[data-list-id="${$this.data('id')}"]`).first();
    let args  = {};

    if ($args) {
      args = JSON.parse($args.html());
    }
    if ($typeahead) {
      $typeahead.UCFPostListSearch(args);
    }
  });

}(jQuery));
