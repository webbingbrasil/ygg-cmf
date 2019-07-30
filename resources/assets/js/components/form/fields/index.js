import YggAutocomplete from './Autocomplete.vue';
import YggTextarea from './Textarea.vue';
import YggText from './Text.vue';
import YggMarkdown from './markdown/Markdown.vue';
import YggNumber from './Number.vue';
import YggUpload from './upload/Upload.vue';
import YggTagInput from './Tags.vue';
import YggDate from './date/Date.vue';
import YggCheck from './Check.vue';
import YggList from './list/List.vue';
import YggSelect from './Select.vue';
import YggHtml from './Html.vue';
import YggGeolocation from './geolocation/Geolocation';
import YggTrix from './wysiwyg/TrixEditor.vue';
import YggDateRange from './date-range/DateRange';

export default {
    'autocomplete': YggAutocomplete,
    'text': YggText,
    'textarea': YggTextarea,
    'markdown': YggMarkdown,
    'number': YggNumber,
    'upload': YggUpload,
    'tags': YggTagInput,
    'date': YggDate,
    'check': YggCheck,
    'list': YggList,
    'select': YggSelect,
    'html': YggHtml,
    'geolocation': YggGeolocation,
    'wysiwyg': YggTrix,
    'daterange': YggDateRange,
};
