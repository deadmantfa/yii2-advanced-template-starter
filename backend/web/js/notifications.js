/**
 * notifications plugin
 */

const Notifications = (function (opts) {
    const options = $.extend({
        pollInterval: 60000,
        xhrTimeout: 2000,
        readLabel: 'read',
        markAsReadLabel: 'mark as read'
    }, opts);
    const elem = $('#' + opts.id);
    const setCount = function (count, decrement) {
        const badge = elem.find('.notifications-count');
        if (decrement) {
            count = parseInt(badge.data('count')) - count;
        }

        if (count > 0) {
            badge.data('count', count).text(count).show();
        } else {
            badge.data('count', 0).text(0).hide();
        }
    };
    const updateCount = function () {
        $.ajax({
            url: options.countUrl,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function (data) {
                setCount(data.count);
            },
            complete: function () {
                startPoll();
            }
        });
    };
    let _updateTimeout;
    const startPoll = function (restart) {
        if (restart && _updateTimeout) {
            clearTimeout(_updateTimeout);
        }
        _updateTimeout = setTimeout(function () {
            updateCount();
        }, opts.pollInterval);
    };
    const markRead = function (mark) {
        mark.off('click').on('click', function () {
            return false;
        });
        mark.attr('title', options.readLabel);
        mark.tooltip('dispose').tooltip();
        mark.closest('.dropdown-item').addClass('read');
    };
    if (!opts.id) {
        throw new Error('Notifications: the param id is required.');
    }

    if (!elem.length) {
        throw Error('Notifications: the element was not found.');
    }


    /**
     * Renders a notification row
     *
     * @param object The notification instance
     * @returns {jQuery|HTMLElement|*}
     */
    const renderRow = function (object) {
        const html = '<div class="dropdown-divider"></div>' +
            '<a href="#" class="dropdown-item mark-read' + (object.read !== '0' ? ' read' : '') + '"' +
            ' data-id="' + object.id + '"' +
            ' data-class="' + object.class + '"' +
            ' data-key="' + object.key + '">' +
            '<span class="text-wrap" style="width: 8rem;">' +
            object.message +
            '</span>' +
            '<span class="float-right text-muted text-sm">' + object.timeago + '</span>' +
            '</a>';
        return $(html);
    };

    const showList = function () {
        const list = elem.find('.notifications-list');
        $.ajax({
            url: options.url,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            //loader: list.parent(),
            success: function (data) {
                let seen = 0;

                if ($.isEmptyObject(data.list)) {
                    list.find('.empty-row span').show();
                }

                $.each(data.list, function (index, object) {
                    if (list.find('>a[data-id="' + object.id + '"]').length) {
                        return;
                    }

                    const item = renderRow(object);
                    item.find('.mark-read').on('click', function (e) {
                        e.stopPropagation();
                        if (item.hasClass('read')) {
                            return;
                        }
                        const mark = $(this);
                        $.ajax({
                            url: options.readUrl,
                            type: "GET",
                            data: {id: item.data('id')},
                            dataType: "json",
                            timeout: opts.xhrTimeout,
                            success: function () {
                                markRead(mark);
                            }
                        });
                    }).tooltip();

                    if (object.url) {
                        item.on('click', function () {
                            document.location = object.url;
                        });
                    }

                    if (object.seen === '0') {
                        seen += 1;
                    }

                    list.append(item);
                });

                setCount(seen, true);

                startPoll(true);
            }
        });
    };

    elem.find('> a[data-toggle="dropdown"]').on('click', function () {
        if (!$(this).parent().hasClass('show')) {
            showList();
        }
    });

    elem.find('.read-all').on('click', function (e) {
        e.stopPropagation();
        const link = $(this);
        $.ajax({
            url: options.readAllUrl,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function () {
                markRead(elem.find('.dropdown-item:not(.read)').find('.mark-read'));
                link.off('click').on('click', function () {
                    return false;
                });
                updateCount();
            }
        });
    });


    // Fire the initial poll
    startPoll();
});

