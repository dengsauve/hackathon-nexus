# Hackathon Nexus — Product Roadmap

This roadmap captures planned product capabilities that extend the current event, team, entry, and judging workflows.

## Event Itinerary

### Objective: Provide a Clear Schedule for Participants and Organizers

- [ ] Allow organizers to create itinerary items for each event
- [ ] Support item types such as check-in, kickoff, workshops, meals, mentor hours, submission deadline, demos, judging, and awards
- [ ] Include start time, end time, location, speaker/host, description, and visibility for each itinerary item
- [ ] Display the itinerary on public event pages when the event is published
- [ ] Display private or organizer-only itinerary notes inside the event management dashboard
- [ ] Allow itinerary changes during a live event
- [ ] Notify registered teams when important itinerary items change
- [ ] Highlight the current and next itinerary item during an active event

### Acceptance Criteria

- [ ] Organizers can build and reorder a complete event schedule
- [ ] Participants can view relevant itinerary details from the event page and portal
- [ ] Live changes are reflected without requiring teams to search through announcements
- [ ] Itinerary visibility rules prevent private organizer notes from appearing publicly

---

## Prize, Placement, and Outcome Management

### Objective: Support Common Hackathon Results

- [ ] Add configurable event outcomes for placements and prizes
- [ ] Include out-of-the-box outcome templates:
  - First place
  - Second place
  - Third place
  - Best overall project
  - Best technical implementation
  - Best design
  - Best use of sponsor API
  - Most lines of code
  - Best disaster story
  - Most ambitious build
  - Community favorite
- [ ] Allow organizers to create custom prize categories per event
- [ ] Allow prizes to be assigned to teams or entries after judging
- [ ] Display final placements and prize winners on event pages after results are finalized
- [ ] Export outcomes for announcements, sponsor reports, and post-event recaps

### Acceptance Criteria

- [ ] Organizers can define prize categories before or during an event
- [ ] Judges or organizers can assign outcomes to submitted entries
- [ ] Finalized outcomes are locked unless reopened by an authorized organizer
- [ ] Public results can be shown or hidden based on event visibility settings

---

## Team Code and Project Links

### Objective: Attach Repository Links to Team Registrations and Entries

- [ ] Allow teams to add GitHub repository links during event registration
- [ ] Allow teams to add GitLab repository links during event registration
- [ ] Allow teams to add other project links such as Bitbucket, demo URLs, docs, Figma, Devpost, or package registries
- [ ] Carry registration links into the project entry workflow so teams do not need to re-enter them
- [ ] Let judges view repository and demo links from the judging interface
- [ ] Validate submitted URLs and show clear labels for each link type
- [ ] Track when links were added or updated for judging auditability

### Acceptance Criteria

- [ ] Teams can attach code and demo links before submission
- [ ] Judges can access links directly while scoring an entry
- [ ] Organizers can see which entries are missing repository or demo links
- [ ] Link updates are visible to authorized users without exposing private entries publicly

---

## Judging Rubrics

### Objective: Provide Ready-Made and Customizable Rubrics

- [ ] Add out-of-the-box rubric templates for common hackathon formats
- [ ] Include default rubric categories such as:
  - Technical execution
  - Product usefulness
  - Creativity
  - Design and usability
  - Presentation quality
  - Impact
  - Completeness
- [ ] Allow organizers to customize category names, descriptions, weights, and scoring ranges
- [ ] Support event-specific rubrics that can be copied from templates
- [ ] Allow multiple rubrics per event when different prize categories need different criteria
- [ ] Preview scoring totals before a rubric is published
- [ ] Version rubrics so score history remains understandable when criteria change

### Acceptance Criteria

- [ ] Organizers can start from a default rubric or create one from scratch
- [ ] Rubrics can be edited until judging starts
- [ ] Published rubrics are visible to assigned judges
- [ ] Score calculations respect category weights and scoring ranges

---

## Real-Time Collaborative Judging

### Objective: Let Judges Work Together During Scoring

- [ ] Allow judges to collaborate on rubric notes in real time
- [ ] Show judge presence on an entry review screen
- [ ] Support shared judging comments separate from private judge notes
- [ ] Allow organizers to see judging progress across all entries
- [ ] Highlight score conflicts or large scoring variance between judges
- [ ] Provide a final deliberation view where judges can compare finalists and adjust outcomes
- [ ] Record final decisions with timestamps and responsible users

### Acceptance Criteria

- [ ] Multiple judges can review the same entry without overwriting each other's work
- [ ] Shared notes update live or near-live for active judges
- [ ] Organizers can identify entries that need additional review
- [ ] Final judging decisions remain auditable after results are published
