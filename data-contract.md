# Data Contract

This document is the authoritative data contract.
Do not add, remove, or rename fields or relationships
unless explicitly instructed.

## Entities

### User
| Field | Type | Notes |
|-----|-----|-----|
| id | UUID | PK |
| email | string | unique, indexed |
| password_hash | string | nullable if OAuth |
| role | enum | admin, organizer, judge, hacker |
| is_active | bool | |
| created_at | timestamp | |

### Event
| Field | Type | Notes |
|-----|-----|-----|
| id | UUID | PK |
| name | string | |
| slug | string | unique |
| starts_at | timestamp | |
| ends_at | timestamp | |
| is_public | bool | |
| created_by | FK → User.id | organizer |

### Team
| Field | Type | Notes |
|-----|-----|-----|
| id | UUID | PK |
| event_id | FK → Event.id | |
| name | string | unique per event |
| created_at | timestamp | |

### TeamMember
| Field | Type | Notes |
|-----|-----|-----|
| team_id | FK → Team.id | composite PK |
| user_id | FK → User.id | composite PK |
| role | enum | owner, member |
| joined_at | timestamp | |

### Submission
| Field | Type | Notes |
|-----|-----|-----|
| id | UUID | PK |
| team_id | FK → Team.id | |
| title | string | |
| description | text | |
| repo_url | string | nullable |
| submitted_at | timestamp | |


## Relationship Rules
- A User may belong to many Teams
- A User may only be on one Team per Event
- An Event must have at least one Organizer (User)
- Only Team owners may submit a Submission


## Authentication

- email/password for signup/login
- google oAuth for signup/login

## Permissions

| Action | Admin | Organizer | Judge | Hacker |
|------|------|-----------|-------|--------|
| Create event | ✅ | ✅ | ❌ | ❌ |
| Join team | ❌ | ❌ | ❌ | ✅ |
| Submit project | ❌ | ❌ | ❌ | ✅ |
| Judge submission | ❌ | ❌ | ✅ | ❌ |
