/* Expand or collapse mobile's header submenus */
jQuery('#header-menu.cnwp-mobile .sub-menu-toggle').click(function() {
    jQuery(this).toggleClass('rotate-pd-90').next('.sub-menu').slideToggle();
});

/* Trigger all mobile's header sub-menu togglers that is expanded sub-menu when them mobile's menu closed  */
jQuery('#site-mobile-header').on('hidden.bs.offcanvas', function() {
    jQuery('#header-menu.cnwp-mobile .sub-menu-toggle.rotate-pd-90').trigger('click');
});

/* Copy short-link or link of a post to clipboard */
jQuery(document).on('click', '.copy-link-button', function() {
    let copyButton = jQuery(this);
    let link = copyButton.data('link');
    citynetCopyToClipboard(link);
    copyButton.html('<i class="icon-clipboard-tick align-middle"></i>');
    setTimeout(function() {
        copyButton.html('<i class="icon-link align-middle"></i>');
    }, 2000);
});

jQuery('#filter-boxes-accordion .search-items').on('keyup', function() {
    let searchKey = jQuery(this).val().toLowerCase();
    let itemsList = jQuery(this).siblings('ul');
    if (searchKey.length > 0) {
        let matchedItems = itemsList.find('input[data-search*="' + searchKey + '"]');
        itemsList.find('ul').removeClass('ms-3');
        itemsList.find('li').addClass('lh-0').children('div').addClass('d-none');
        matchedItems.parent('div').removeClass('d-none');
        matchedItems.parents('li').removeClass('lh-0');
    } else {
        itemsList.find('li').removeClass('lh-0').children('div').removeClass('d-none');
        itemsList.find('ul').addClass('ms-3');
    }
});

let themeQueryPosts = {};
function citynetBuildQueryPosts() {
    let loadMoreBtn = jQuery('#load-more-cards');
    themeQueryPosts = loadMoreBtn.data('query');
    themeQueryPosts.queryUI = {};
}

jQuery('#filter-boxes-accordion input[type="checkbox"]').click(function() {
    let input = jQuery(this);
    let box = input.closest('.accordion-item'); 
    let checked = input.is(':checked');
    let filter = {
        type: box.data('type'),
        boxTitle: box.data('title'),
        boxValue: box.data('value'),
        itemTitle: input.data('title'),
        itemValue: parseInt(input.val()),
        isRadio: false
    };

    citynetBuildQueryPosts();
    themeQueryPosts.count = themeQueryPosts.ppp;
    if (checked) {
        citynetAddQueryFilter(filter);
    } else {
        citynetRemoveQueryFilter(filter);
    }
    themeQueryPosts.type = 'change-filters';
    citynetQueryPosts();
});

jQuery(document).on('click', '#filter-boxes-accordion #selected-filters-wrapper .delete-filter', function() {
    let deleteButton = jQuery(this);
    let filter = deleteButton.parent('.filter');
    let filterGroup = filter.closest('.filter-group');
    let groupNameParts = filterGroup.data('name').split('-');
    let filterInChecklists = jQuery('#filter-boxes-accordion .filter-wrapper[data-type="' + groupNameParts[0] + '"][data-value="' + groupNameParts[1] + '"] input[value="' + filter.data('value') + '"]');
    
    if (filterInChecklists.length) filterInChecklists.trigger('click');
    filter.remove();
});

/* Run ajax load-more in all archives and etc. */
jQuery('#load-more-cards').click(function() {
    citynetBuildQueryPosts();
    themeQueryPosts.type = 'load-more';
    citynetQueryPosts();
});

jQuery(document).ready(function($) {
    
});

/* Adds placeholder cards to the template */
function citynetAddPlaceHolders() {
    themeQueryPosts.placeHolder = themeQueryPosts.cardsWrapper.children('.cnwp-placeholder-col');
    for (let i = 1; i <= themeQueryPosts.count; i++) {
        let clonedPlaceHolder = themeQueryPosts.placeHolder.clone().addClass('cnwp-cloned-placeholder').removeClass('cnwp-placeholder-col d-none');
        themeQueryPosts.placeHolder.before(clonedPlaceHolder);
    }
}

/* Removes placeholder cards from the template */
function citynetRemovePlaceHolders() {
    let clonedPlaceHolders = themeQueryPosts.placeHolder.siblings('.cnwp-cloned-placeholder');
    clonedPlaceHolders.remove();
}

/* Removes current posts cards from the template */
function citynetRemovePostsCards() {
    let postsCards = themeQueryPosts.placeHolder.siblings('.col:not(.cnwp-cloned-placeholder)');
    postsCards.remove();
}

/* Adds loaded posts cards from ajax request to the template */
function citynetAddNewCards() {
    if (!themeQueryPosts.result.success) return false;
    themeQueryPosts.placeHolder.before(themeQueryPosts.result.data.items);

    let loadMoreButton = jQuery('#load-more-cards');
    if (themeQueryPosts.result.data.more) {
        loadMoreButton.removeClass('d-none');
    } else {
        loadMoreButton.addClass('d-none');
    }
}

/* Handles all type of possible copy ways a value to the clipboard */
function citynetCopyToClipboard(value) {
    try {
        navigator.clipboard.writeText(value).catch(function() {
            cnCopyToClipboardLegacy(value);
        });
    } catch (e) {
        cnCopyToClipboardLegacy(value);
    }
}

/* Legacy function for copy a value to the clipboard */
function cnCopyToClipboardLegacy(value) {
    let el = document.createElement('textarea');
    el.value = value;
    el.style.position = 'absolute';
    el.style.opacity = '0';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
}

function citynetAddQueryFilter(filter) {
    /* Adds filter to backend ajax posts query */
    let queryItem = filter.type + '|' + filter.boxValue + '|' + filter.itemValue;
    themeQueryPosts.query.filters.push(queryItem);

    /* Adds filter to selected filters box in frontend */
    let selectedFiltersBox = jQuery('#filter-boxes-accordion').children('#selected-filters-wrapper');
    selectedFiltersBox.removeClass('d-none');

    let groupName = filter.type + '-' + filter.boxValue;
    let groupElement = selectedFiltersBox.find('.filter-group[data-name="' + groupName + '"]');
    if (!groupElement.length) {
        let groupHtml = '<div class="filter-group mb-2" data-name="' + groupName + '">\
            <span class="fw-bold">' + filter.boxTitle + '</span>\
            <div class="group-items d-flex flex-wrap"></div>\
        </div>';
        selectedFiltersBox.find('.accordion-body').append(groupHtml);
        groupElement = selectedFiltersBox.find('.filter-group[data-name="' + groupName + '"]');
    }

    let groupItems = groupElement.children('.group-items');
    if (filter.isRadio) groupItems.children('.filter').remove();

    let itemHtml = '<span class="filter badge text-bg-primary fw-normal py-2 me-1 mb-1" data-value="' + filter.itemValue + '">' +
        filter.itemTitle + '<i class="delete-filter icon-remove ms-1 align-middle" role="button"></i>\
    </span>';
    groupItems.append(itemHtml);
}

function citynetRemoveQueryFilter(filter) {
    /* Removes filter from backend ajax posts query */
    themeQueryPosts.query.filters = themeQueryPosts.query.filters.filter(
        value => value !== filter.type + '|' + filter.boxValue + '|' + filter.itemValue
    );

    /* Removes filter from selected filters box in frontend */
    let selectedFiltersBox = jQuery('#filter-boxes-accordion').children('#selected-filters-wrapper');

    let groupName = filter.type + '-' + filter.boxValue;
    let groupElement = selectedFiltersBox.find('.filter-group[data-name="' + groupName + '"]');
    let groupItems = groupElement.children('.group-items');
    
    groupItems.children('.filter[data-value="' + filter.itemValue + '"]').remove();
    if (!groupItems.children('.filter').length) groupElement.remove();
    if (!selectedFiltersBox.find('.filter-group').length) selectedFiltersBox.addClass('d-none');
}

function citynetQueryPosts() {
    if (themeAjax.waiting) return false;

    themeAjax.waiting = true;
    themeQueryPosts.cardsWrapper = jQuery('#cards-wrapper');
    citynetAddPlaceHolders();
    if (themeQueryPosts.type == 'change-filters') citynetRemovePostsCards();
    themeQueryPosts.query.paged = (themeQueryPosts.type == 'load-more')? themeQueryPosts.query.paged + 1 : 1;

    jQuery.ajax({
        url: themeAjax.url,
        data: themeQueryPosts.query,
        success: function(result) {
            themeQueryPosts.result = result;
            themeQueryPosts.count = result.data.more;
            citynetRemovePlaceHolders();
            citynetAddNewCards();
            themeAjax.waiting = false;
        }
    });
}

jQuery(document).ready(function($) {

    $('#filter-boxes-accordion input[type="checkbox"]:checked').each(function() {
        let input = $(this);
        let box = input.closest('.accordion-item');
        let filter = {
            type: box.data('type'),
            boxTitle: box.data('title'),
            boxValue: box.data('value'),
            itemTitle: input.data('title'),
            itemValue: parseInt(input.val()),
            isRadio: false
        };

        if (!themeQueryPosts.query) citynetBuildQueryPosts();
        if (!themeQueryPosts.query.filters) themeQueryPosts.query. filters = [];


        let key = filter.type + '|' + filter.boxValue + '|' + filter.itemValue;
        if (!themeQueryPosts.query.filters.includes(key)) {
            themeQueryPosts.query.filters.push(key);
            citynetAddQueryFilter(filter);
        }
    });

});
