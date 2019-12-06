const axios = require("axios");

export class ProjectRepository {
  constructor(baseUrl, jwt) {
    this._axios = axios.create({
      baseURL: baseUrl,
      headers: {
        "XX-LIDE-Authorization": `Bearer ${jwt}`,
        "Access-Control-Allow-Origin": "*"
      }
    });
  }

  readProject(id) {
    const url = `/api/project/${id}`;
    return this._axios.get(url).then(response => {
      return response.data;
    });
  }

  readProjectIndex() {
    const url = `/api/projects`;
    return this._axios.get(url).then(response => response.data);
  }

  createProject(data) {
    const url = `/api/project`;
    return this._axios.post(url, data).then(response => response.data);
  }

  deleteProject(projectId) {
    const url = `/api/project/${projectId}`;
    return this._axios.delete(url);
  }
}
