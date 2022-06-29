import Tool from './components/Tool';

Nova.booting((app, store) => {
    app.component('report-page-generator', Tool);
});
