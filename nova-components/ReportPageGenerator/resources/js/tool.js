import Tool from '@/components/Tool';
import InputField from '@/components/InputField.vue';
import SelectField from '@/components/SelectField.vue';
import ReportPreview from './pages/ReportPreview';

Nova.booting((app, store) => {
    app.component('report-page-generator', Tool);
    app.component('TwInputField', InputField);
    app.component('TwSelectField', SelectField);
    Nova.inertia('ReportPreview', ReportPreview);
});
