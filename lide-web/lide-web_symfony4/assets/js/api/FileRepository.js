const axios = require("axios");

export class FileRepository {
  constructor(baseUrl, jwt, project) {
    this._axios = axios.create({
      baseURL: baseUrl,
      headers: {
        "XX-LIDE-Authorization": `Bearer ${jwt}`,
        "Access-Control-Allow-Origin": "*"
      }
    });
    this._project = project;
  }

  readProjectFiles() {
    const url = `/api/project/${this._project.id}/files`;
    return this._axios.get(url).then(response => {
      return response.data;
    });
  }

  //------------------------------------------------------------------------------------------------------
  // Files CRUD
  //

  /**
   *
   * @param {Object} file
   */
  createFile(file) {
    const url = `/api/project/${this._project.id}/files`;
    return this._axios.post(url, file).then(response => {
      delete response.data.project;
      return response.data;
    });
  }

  /**
   *
   * @param {Number} fileId
   * @param {Boolean} withContent
   */
  readFile(fileId, withContent = true) {
    const url = `/api/project/${
      this._project.id
    }/files/${fileId}?withContent=${withContent}`;
    return this._axios.get(url).then(response => {
      let obj = response.data.data;
      if (withContent) {
        obj.content = response.data.content;
      }
      obj.project = this._project;
      return obj;
    });
  }

  /**
   *
   * @param {Object} file
   */
  updateFile(file) {
    let url = `/api/project/${this._project.id}/files/${file.id}`;
    return this._axios.put(url, file);
  }

  /**
   *
   * @param {Object} file
   */
  deleteFile(file) {
    let url = `/api/project/${this._project.id}/files/${file.id}`;
    return this._axios.delete(url);
  }
}
