import IndexField from "./components/IndexField";
import Popover from "./components/popover.vue";
import App from "./app";

Nova.booting((app, store) => {
  window.NovaEditableField = App;
  app.component("popover", Popover);
  app.component("index-nova-editable-field", IndexField);
});
