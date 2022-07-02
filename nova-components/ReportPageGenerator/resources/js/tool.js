import Tool from './components/Tool';
import FieldWrapper from './components/FieldWrapper.vue';
import InputField from './components/InputField.vue';
import SelectField from './components/SelectField.vue';

Nova.booting((app, store) => {
    app.component('report-page-generator', Tool);
    app.component('MrwFieldWrapper', FieldWrapper);
    app.component('MrwInputField', InputField);
    app.component('MrwSelectField', SelectField);
});
