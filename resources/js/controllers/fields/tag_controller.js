import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        let url = this.data.get('url');
        if(url.length === 0) {
            url = platform.prefix('/resource/tags/')
        }
        const select = this.element.querySelector('select');

        setTimeout(() => {
            $(select).select2({
                theme: 'bootstrap',
                templateResult: (state) => {
                    if (!state.id || !state.count) {
                        return state.text;
                    }
                    return $(`<span>${state.text}</span><span class="pull-right badge bg-info">${state.count}</span>`);
                },
                createTag(tag) {
                    return {
                        id: tag.term,
                        text: tag.term,
                    };
                },
                escapeMarkup(m) {
                    return m;
                },
                width: '100%',
                tags: true,
                cache: true,
                ajax: {
                    url(params) {
                        return url + params.term;
                    },
                    delay: 340,
                    processResults(data) {
                        return {
                            results: data,
                        };
                    },
                },
            });
        }, 100);
    }
}
