export default class WsConnector {
  /**
   *
   * @param {String} url
   * @param {Function} onopen
   * @param {Boolean} log
   */
  constructor(url, onopen = null, log = false, onerror = null) {
    this._log = log;
    this._ws = new WebSocket(url);
    console.log(this._ws);

    this._isConnected = false;
    this._onerror = onerror;

    this._ws.onopen = () => {
      this._isConnected = true;
      console.log(this._isConnected );
      if (onopen != null) {
        onopen();
      }
    };

    this._ws.onmessage = event => {
      if (this._log) {
        console.log("Received message", event);
      }
      let msg = JSON.parse(event.data);
      if (this._log) console.log(msg);

      if (this._callbacks.hasOwnProperty(msg.type)) {
        this._callbacks[msg.type](msg.data);
      } else if (this._log) {
        console.log(`No handler for message of type ${msg.type}`);
      }
    };

    this._ws.onerror = err => {
      if (this._log) {
        console.error(err);
      }
      if (this._onerror) {
        onerror(err);
      }
    };

    this._callbacks = {};
  }

  send(type, data) {
    const msg = {
      type,
      data
    };
    if (this._log) {
      console.log("Sending message", msg);
    }
    this._ws.send(JSON.stringify(msg));
  }

  /**
   *
   * @param {string} type
   * @param {Function} callback
   */
  setCallbackForType(type, callback) {
    this._callbacks[type] = callback;
  }
}
