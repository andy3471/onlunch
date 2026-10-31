import '@inertiajs/core';

export interface SharedAuthUser {
  id: string;
  name: string;
  email: string;
  is_admin: boolean;
  can_manage_time_off: boolean;
}

export interface SharedAuth {
  user: SharedAuthUser | null;
}

export interface SharedFlash {
  message: string | null;
  error: string | null;
}

export interface SharedConfig {
  appName: string;
  registerEnabled: boolean;
  resetPasswordEnabled: boolean;
  tasksEnabled: boolean;
}

export interface SharedCurrentSite {
  id: string;
  name: string;
  slug: string;
}

export interface SharedUserSite extends SharedCurrentSite {
  url: string;
  isCurrent: boolean;
}

declare module '@inertiajs/core' {
  interface PageProps {
    auth: SharedAuth;
    flash: SharedFlash;
    config: SharedConfig;
    currentSite: SharedCurrentSite | null;
    userSites: SharedUserSite[];
  }
}
