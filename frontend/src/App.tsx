import { useEffect, useMemo, useState } from "react";

type HealthResponse = {
  status: string;
};

type AuthUser = {
  id: string;
  email: string;
  role: "admin" | "organizer" | "judge" | "hacker";
  is_active: boolean;
  created_at: string;
};

type GoogleLoginResponse = {
  authorization_url: string;
};

type GoogleCallbackResponse = {
  user: AuthUser;
  is_new_user: boolean;
};

const viteEnv = (import.meta as unknown as { env?: { VITE_API_URL?: string } }).env;
const API_URL = viteEnv?.VITE_API_URL ?? "http://localhost:8000";
const OAUTH_CALLBACK_PATH = "/oauth/callback";
const AUTH_STORAGE_KEY = "hn_auth_user";

function getOAuthRedirectUri(): string {
  return `${window.location.origin}${OAUTH_CALLBACK_PATH}`;
}

export default function App() {
  const [status, setStatus] = useState<"loading" | "ok" | "error">("loading");
  const [error, setError] = useState<string | null>(null);
  const [authUser, setAuthUser] = useState<AuthUser | null>(null);
  const [authLoading, setAuthLoading] = useState(false);
  const [authError, setAuthError] = useState<string | null>(null);
  const [authNotice, setAuthNotice] = useState<string | null>(null);

  const isOAuthCallbackRoute = useMemo(
    () => window.location.pathname === OAUTH_CALLBACK_PATH,
    [],
  );

  useEffect(() => {
    const raw = localStorage.getItem(AUTH_STORAGE_KEY);
    if (!raw) {
      return;
    }

    try {
      const parsed = JSON.parse(raw) as AuthUser;
      setAuthUser(parsed);
    } catch {
      localStorage.removeItem(AUTH_STORAGE_KEY);
    }
  }, []);

  useEffect(() => {
    const run = async () => {
      try {
        const res = await fetch(`${API_URL}/health`);
        if (!res.ok) {
          throw new Error(`HTTP ${res.status}`);
        }
        const data = (await res.json()) as HealthResponse;
        setStatus(data.status === "ok" ? "ok" : "error");
      } catch (err) {
        const message = err instanceof Error ? err.message : "Unknown error";
        setError(message);
        setStatus("error");
      }
    };

    void run();
  }, []);

  useEffect(() => {
    if (!isOAuthCallbackRoute) {
      return;
    }

    const run = async () => {
      setAuthLoading(true);
      setAuthError(null);
      setAuthNotice("Completing Google sign-in...");

      const params = new URLSearchParams(window.location.search);
      const oauthError = params.get("error");
      const code = params.get("code");

      if (oauthError) {
        setAuthError(`Google OAuth error: ${oauthError}`);
        setAuthLoading(false);
        return;
      }

      if (!code) {
        setAuthError("Missing OAuth code in callback URL.");
        setAuthLoading(false);
        return;
      }

      try {
        const redirectUri = getOAuthRedirectUri();
        const callbackUrl = `${API_URL}/auth/google/callback?code=${encodeURIComponent(code)}&redirect_uri=${encodeURIComponent(redirectUri)}`;
        const res = await fetch(callbackUrl);
        if (!res.ok) {
          let detail = `HTTP ${res.status}`;
          try {
            const payload = (await res.json()) as { detail?: string };
            if (payload.detail) {
              detail = payload.detail;
            }
          } catch {
            // Leave default detail.
          }
          throw new Error(detail);
        }

        const payload = (await res.json()) as GoogleCallbackResponse;
        setAuthUser(payload.user);
        localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(payload.user));
        setAuthNotice(
          payload.is_new_user
            ? "Google sign-up completed."
            : "Google sign-in completed.",
        );
        window.history.replaceState({}, document.title, "/");
      } catch (err) {
        const message = err instanceof Error ? err.message : "OAuth callback failed.";
        setAuthError(message);
        setAuthNotice(null);
      } finally {
        setAuthLoading(false);
      }
    };

    void run();
  }, [isOAuthCallbackRoute]);

  const handleGoogleSignIn = async () => {
    setAuthLoading(true);
    setAuthError(null);
    setAuthNotice(null);
    try {
      const redirectUri = getOAuthRedirectUri();
      const res = await fetch(
        `${API_URL}/auth/google/login?redirect_uri=${encodeURIComponent(redirectUri)}`,
      );
      if (!res.ok) {
        throw new Error(`HTTP ${res.status}`);
      }
      const payload = (await res.json()) as GoogleLoginResponse;
      window.location.assign(payload.authorization_url);
    } catch (err) {
      const message = err instanceof Error ? err.message : "Failed to start Google sign-in.";
      setAuthError(message);
      setAuthLoading(false);
    }
  };

  const handleSignOut = () => {
    localStorage.removeItem(AUTH_STORAGE_KEY);
    setAuthUser(null);
    setAuthNotice("Signed out.");
    setAuthError(null);
  };

  return (
    <div className="app">
      <header className="topbar">
        <div className="brand">
          <span className="brand-mark">HN</span>
          <div>
            <p className="brand-title">Hackathon Nexus</p>
            <p className="brand-subtitle">Signal. Ship. Repeat.</p>
          </div>
        </div>
        <nav className="nav">
          <a href="#welcome">Welcome</a>
          <a href="#profile">Profile</a>
          <a href="#team">Team</a>
          <a href="#event">Event</a>
        </nav>
        <div className="topbar-right">
          <div className="status">
            <span>Backend</span>
            {status === "loading" && <span className="pill">Loading</span>}
            {status === "ok" && <span className="pill ok">Healthy</span>}
            {status === "error" && (
              <span className="pill error">Error{error ? `: ${error}` : ""}</span>
            )}
          </div>
          <div className="auth-status">
            {authUser ? (
              <>
                <span className="pill ok">Signed in</span>
                <span className="auth-user">
                  {authUser.email} ({authUser.role})
                </span>
                <button type="button" className="ghost-btn tiny" onClick={handleSignOut}>
                  Sign out
                </button>
              </>
            ) : (
              <button
                type="button"
                className="neon-btn small"
                onClick={handleGoogleSignIn}
                disabled={authLoading}
              >
                {authLoading ? "Starting..." : "Sign in with Google"}
              </button>
            )}
          </div>
        </div>
      </header>

      <main>
        {(authLoading || authError || authNotice) && (
          <section className="panel auth-panel">
            <p className="kicker">Authentication</p>
            {authLoading && <p className="card-body">Waiting for Google OAuth response...</p>}
            {authNotice && <p className="card-body auth-ok">{authNotice}</p>}
            {authError && <p className="card-body auth-error">{authError}</p>}
          </section>
        )}

        <section id="welcome" className="panel hero">
          <div className="hero-content">
            <p className="kicker">Welcome page</p>
            <h1>Build loud. Ship clean. Own the leaderboard.</h1>
            <p className="lead">
              Hackathon Nexus is your command center for teams, submissions, and
              judging. Track your mission, rally your crew, and deploy with
              confidence.
            </p>
            <div className="hero-actions">
              <button className="neon-btn" type="button">
                Create a team
              </button>
              <button className="ghost-btn" type="button">
                Explore events
              </button>
            </div>
            <div className="hero-stats">
              <div>
                <p className="stat-label">Active events</p>
                <p className="stat-value">04</p>
              </div>
              <div>
                <p className="stat-label">Teams online</p>
                <p className="stat-value">128</p>
              </div>
              <div>
                <p className="stat-label">Submissions</p>
                <p className="stat-value">56</p>
              </div>
            </div>
          </div>
          <div className="hero-frame">
            <div className="grid-card">
              <p className="card-title">Live Ops</p>
              <ul>
                <li>Next sync: 7:30 PM</li>
                <li>Build window: 12 hrs</li>
                <li>Theme: Civic Systems</li>
              </ul>
            </div>
            <div className="grid-card accent">
              <p className="card-title">Mentor queue</p>
              <p className="queue">02 waiting</p>
              <button className="neon-btn small" type="button">
                Join queue
              </button>
            </div>
          </div>
        </section>

        <section id="profile" className="panel">
          <div className="panel-header">
            <div>
              <p className="kicker">Profile page</p>
              <h2>Agent profile</h2>
            </div>
            <button className="ghost-btn" type="button">
              Edit profile
            </button>
          </div>
          <div className="profile-grid">
            <div className="profile-card">
              <div className="avatar">MK</div>
              <div>
                <p className="profile-name">Miko Kline</p>
                <p className="profile-handle">@signalcraft</p>
                <p className="profile-meta">Hacker - NYC</p>
              </div>
              <div className="profile-tags">
                <span>Full-stack</span>
                <span>Realtime</span>
                <span>ML Ops</span>
              </div>
            </div>
            <div className="profile-card">
              <p className="card-title">Focus</p>
              <p className="card-body">
                Building a civic triage tool for emergency responders with live
                geofencing alerts and trust-scored reports.
              </p>
              <div className="meter">
                <span>Progress</span>
                <div className="meter-bar">
                  <div className="meter-fill" />
                </div>
              </div>
            </div>
            <div className="profile-card">
              <p className="card-title">Badges</p>
              <div className="badge-grid">
                <span>Ship it</span>
                <span>Mentor+</span>
                <span>Night ops</span>
                <span>Clean UI</span>
              </div>
            </div>
          </div>
        </section>

        <section id="team" className="panel">
          <div className="panel-header">
            <div>
              <p className="kicker">Team page</p>
              <h2>Team: Neon Circuit</h2>
            </div>
            <button className="neon-btn small" type="button">
              Invite member
            </button>
          </div>
          <div className="team-grid">
            {[
              { name: "Ari Chen", role: "Owner", skill: "Product" },
              { name: "Sable Roy", role: "Member", skill: "Frontend" },
              { name: "Zion Patel", role: "Member", skill: "Backend" },
              { name: "Vera Holt", role: "Member", skill: "AI/ML" },
            ].map((member) => (
              <div className="team-card" key={member.name}>
                <div className="avatar small">{member.name.slice(0, 2)}</div>
                <div>
                  <p className="team-name">{member.name}</p>
                  <p className="team-meta">
                    {member.role} - {member.skill}
                  </p>
                </div>
                <button className="ghost-btn tiny" type="button">
                  Message
                </button>
              </div>
            ))}
          </div>
        </section>

        <section id="event" className="panel event">
          <div className="panel-header">
            <div>
              <p className="kicker">Event page</p>
              <h2>Hackathon: Nexus Protocol</h2>
            </div>
            <button className="ghost-btn" type="button">
              View rules
            </button>
          </div>
          <div className="event-grid">
            <div className="event-card">
              <p className="card-title">Timeline</p>
              <ul>
                <li>Kickoff: Feb 12, 7:00 PM</li>
                <li>Build window: 24 hrs</li>
                <li>Submissions: Feb 13, 7:00 PM</li>
                <li>Judging: Feb 14, 10:00 AM</li>
              </ul>
            </div>
            <div className="event-card">
              <p className="card-title">Submission form</p>
              <form className="submission">
                <label>
                  Project title
                  <input type="text" placeholder="Project Echo" />
                </label>
                <label>
                  Repo link
                  <input type="url" placeholder="https://github.com/..." />
                </label>
                <label>
                  Summary
                  <textarea placeholder="What did you build?" rows={4} />
                </label>
                <button className="neon-btn" type="submit">
                  Submit project
                </button>
              </form>
            </div>
            <div className="event-card">
              <p className="card-title">Resources</p>
              <div className="resource-list">
                <button className="ghost-btn" type="button">
                  Starter kit
                </button>
                <button className="ghost-btn" type="button">
                  API docs
                </button>
                <button className="ghost-btn" type="button">
                  Design system
                </button>
              </div>
              <p className="card-body subtle">
                Need help? Jump into the mentor queue or ping judges for early
                feedback before deadline.
              </p>
            </div>
          </div>
        </section>
      </main>
    </div>
  );
}
