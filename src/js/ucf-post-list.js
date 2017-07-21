var PostTypeSearchDataManager = {
  'searches' : [],
  'register' : function(search) {
    this.searches.push(search);
  }
};
var PostTypeSearchData = function(column_count, column_width, data) {
  this.column_count = column_count;
  this.column_width = column_width;
  this.data         = data;
};

var PostTypeSearch = function ($) {
  $('.post-type-search')
    .each(function (post_type_search_index, post_type_search) {
      post_type_search = $(post_type_search);
      var form = post_type_search.find('.post-type-search-form');
      var field = form.find('input[type="text"]');
      var results = post_type_search.find('.post-type-search-results');
      var by_term = post_type_search.find('.post-type-search-term');
      var by_alpha = post_type_search.find('.post-type-search-alpha');
      var sorting = post_type_search.find('.post-type-search-sorting');
      var sorting_filters = sorting.find('.sorting-filter');

      var post_type_search_data = null;
      var search_data_set = null;
      var column_count = null;
      var column_width = null;

      var typing_timer = null;
      var typing_delay = 300; // milliseconds

      var prev_post_id_sum = null; // Sum of result post IDs. Used to cache results

      var MINIMUM_SEARCH_MATCH_LENGTH = 2;

      // Get the post data for this search
      post_type_search_data = PostTypeSearchDataManager.searches[post_type_search_index];
      if (typeof post_type_search_data === 'undefined') { // Search data missing
        return false;
      }

      search_data_set = post_type_search_data.data;
      column_count = post_type_search_data.column_count;
      column_width = post_type_search_data.column_width;

      if (column_count === 0 || column_width === '') { // Invalid dimensions
        return false;
      }

      // Hide individual result sections by alpha
      by_alpha.find('.post-type-search-section').hide();


      // Sorting toggle
      function activateSectionBySortingFilter($filterLink) {
        if ($filterLink.hasClass('sorting-filter-all')) {
          by_alpha.fadeOut('fast', function () {
            by_term.fadeIn();
            sorting.find('.active').removeClass('active');
            post_type_search.find('.post-type-search-section.active')
              .removeClass('active')
              .hide();
            $filterLink.addClass('active');
          });
        }
        else {
          by_term.fadeOut('fast', function () {
            by_alpha.fadeIn();
            sorting.find('.active').removeClass('active');
            post_type_search.find('.post-type-search-section.active')
              .removeClass('active')
              .hide();
            $filterLink.addClass('active');
            $($filterLink.attr('href'))
              .fadeIn()
              .addClass('active');
          });
        }
      }

      // Check for location hash on page load
      if (window.location.hash) {
        var hash = window.location.hash,
          $link = $('.sorting-filter[href="' + hash.toLowerCase() + '"]:eq(0)');

        if ($link.length) {
          activateSectionBySortingFilter($link);
        }
        else {
          activateSectionBySortingFilter(sorting_filters.filter('.sorting-filter-all'));
        }
      }
      else {
        activateSectionBySortingFilter(sorting_filters.filter('.sorting-filter-all'));
      }

      sorting_filters.on('click', function (e) {
        e.preventDefault();

        var $link = $(this);

        // Update location hash in URL without page jump
        window.location.hash = $link.attr('href');

        activateSectionBySortingFilter($link);
      });


      // Search form
      form
        .submit(function (event) {
          // Don't allow the form to be submitted
          event.preventDefault();
          perform_search(field.val());
        });
      field
        .keyup(function () {
          // Use a timer to determine when the user is done typing
          if (typing_timer !== null) {
            clearTimeout(typing_timer);
          }
          typing_timer = setTimeout(function () { form.trigger('submit'); }, typing_delay);
        });

      function display_search_message(message) {
        results.empty();
        results.append($('<p class="post-type-search-message"><big>' + message + '</big></p>'));
        results.show();
      }

      function perform_search(search_term) {
        var matches = [],
          elements = [],
          elements_per_column = null,
          columns = [],
          post_id_sum = 0;

        if (search_term.length < MINIMUM_SEARCH_MATCH_LENGTH) {
          results.empty();
          results.hide();
          prev_post_id_sum = null;
          return;
        }
        // This is gross, but post data has to be grouped and looped through
        // this way to maintain the order set in the shortcode, and we still
        // need reliable access to post_id. See shortcodes.php.
        $.each(search_data_set, function (order_key, post_obj) {
          $.each(post_obj, function (post_id, post_data) {
            $.each(post_data, function (term_key, term) {
              if (term.indexOf(search_term.toLowerCase()) !== -1) {
                matches.push(post_id);
                return false;
              }
            });
          });
        });

        if(matches.length === 0) {
          display_search_message('No results were found.');
        } else {

          // Copy the associated elements
          $.each(matches, function(match_index, post_id) {

            var element     = by_term.find('li[data-post-id="' + post_id + '"]:eq(0)'),
              post_id_int = parseInt(post_id, 10);
            post_id_sum += post_id_int;
            if(element.length === 1) {
              elements.push(element.clone());
            }
          });

          if(elements.length === 0) {
            display_search_message('No results were found.');
          } else {

            // Are the results the same as last time?
            if(post_id_sum !== prev_post_id_sum) {
              results.empty();
              prev_post_id_sum = post_id_sum;

              // Slice the elements into their respective columns
              elements_per_column = Math.ceil(elements.length / column_count);
              for(var i = 0; i < column_count; i++) {
                var start = i * elements_per_column,
                  end   = start + elements_per_column;
                if(elements.length > start) {
                  columns[i] = elements.slice(start, end);
                }
              }

              // Setup results HTML
              results.append($('<div class="row"></div>'));
              $.each(columns, function(column_index, column_elements) {
                var column_wrap = $('<div class="' + column_width + '"><ul class="result-list"></ul></div>'),
                  column_list = column_wrap.find('ul');

                $.each(column_elements, function(element_index, element) {
                  column_list.append($(element));
                });
                results.find('div[class="row"]').append(column_wrap);
              });
              results.show();
            }
          }
        }
      }
    });
};

$(PostTypeSearch);
