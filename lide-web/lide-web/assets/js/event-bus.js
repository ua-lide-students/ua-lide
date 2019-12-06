import Vue from "vue";
export const EventBus = new Vue();

export const events = {
  NOTIFICATION: "notification",
  START_EXEC: "start_exec",
  OPEN_FILE: "open_file", //Payload : file object

  OPEN_DRAWER: "open_drawer", // No payload
  CLOSE_DRAWER: "close_drawer", //No payload_
  TOGGLE_DRAWER: "toggle_drawer", //No payload

  SUBMIT_NEW_FILE: "submit_new_file", // Payload : file object
  DUPLICATE_FILE: "duplicate_file", // Payload : index of the files in files array
  REMOVE_FILE: "remove_file", // Payload : file object,

  CHANGE_USER_OPTIONS: "change_user_options"
};

/**
 *
 * @param {String} type one of : {info, error, success, warning}
 * @param {String} message
 * @param {Number} length notificatiion duration in ms
 */
export const notify = (type, message, length = 1500) => {
  EventBus.$emit(events.NOTIFICATION, {
    type,
    message,
    length
  });
};
