const axios = require("axios");

import { FileRepository } from "./FileRepository";
import { ProjectRepository } from "./ProjectRepository";

export const repositoriesContainer = {
  fileRepository: null,
  projectRepository: null
};

export function initProjectRepository(baseUrl, jwt) {
  repositoriesContainer.projectRepository = new ProjectRepository(baseUrl, jwt);
}

export function initFileRepository(baseUrl, jwt, project) {
  repositoriesContainer.fileRepository = new FileRepository(
    baseUrl,
    jwt,
    project
  );
}

export class Api {
  constructor(lideWebBaseUrl) {
    this._lideWebBaseUrl = lideWebBaseUrl;
    this._webAxios = axios.create({
      baseURL: lideWebBaseUrl,
      timeout: 2000
    });
  }

  /**
   *
   * @param {Object} configuration
   * @returns {Promise<Object>}
   */
  saveUserConfiguration(configuration) {
    const route = `${this._lideWebBaseUrl}/user_config`; //To be changed
    return this._webAxios.post(route, configuration);
  }
}
