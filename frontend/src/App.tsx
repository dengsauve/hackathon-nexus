import { useEffect, useState } from "react";

type HealthResponse = {
  status: string;
};

const API_URL = import.meta.env.VITE_API_URL ?? "http://localhost:8000";

export default function App() {
  const [status, setStatus] = useState<"loading" | "ok" | "error">("loading");
  const [error, setError] = useState<string | null>(null);

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

    run();
  }, []);

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
        <div className="status">
          <span>Backend</span>
          {status === "loading" && <span className="pill">Loading</span>}
          {status === "ok" && <span className="pill ok">Healthy</span>}
          {status === "error" && (
            <span className="pill error">Error{error ? `: ${error}` : ""}</span>
          )}
        </div>
      </header>

      <main>
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
              <button className="neon-btn">Create a team</button>
              <button className="ghost-btn">Explore events</button>
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
              <button className="neon-btn small">Join queue</button>
            </div>
          </div>
        </section>

        <section id="profile" className="panel">
          <div className="panel-header">
            <div>
              <p className="kicker">Profile page</p>
              <h2>Agent profile</h2>
            </div>
            <button className="ghost-btn">Edit profile</button>
          </div>
          <div className="profile-grid">
            <div className="profile-card">
              <div className="avatar">MK</div>
              <div>
                <p className="profile-name">Miko Kline</p>
                <p className="profile-handle">@signalcraft</p>
                <p className="profile-meta">Hacker • NYC</p>
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
            <button className="neon-btn small">Invite member</button>
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
                    {member.role} • {member.skill}
                  </p>
                </div>
                <button className="ghost-btn tiny">Message</button>
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
            <button className="ghost-btn">View rules</button>
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
                <button className="neon-btn">Submit project</button>
              </form>
            </div>
            <div className="event-card">
              <p className="card-title">Resources</p>
              <div className="resource-list">
                <button className="ghost-btn">Starter kit</button>
                <button className="ghost-btn">API docs</button>
                <button className="ghost-btn">Design system</button>
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
