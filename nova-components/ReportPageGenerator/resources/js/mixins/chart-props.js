export default {
    chartId: {
        type: String,
        default: 'default-chart-id',
    },
    datasetIdKey: {
        type: String,
        default: 'label',
    },
    width: {
        type: Number,
        default: 400,
    },
    height: {
        type: Number,
        default: 400,
    },
    cssClasses: {
        default: '',
        type: String,
    },
    styles: {
        type: Object,
        default: () => {},
    },
    plugins: {
        type: Array,
        default: () => [],
    },
    data: {
        type: Object,
        default: () => {},
    },
    options: {
        type: Object,
        default: () => {},
    },
};
