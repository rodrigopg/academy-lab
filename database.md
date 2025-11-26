# Database Schema

## Core Infrastructure

### cache
| Column | Type | Notes |
| --- | --- | --- |
| key | string | Primary key |
| value | mediumText |  |
| expiration | integer | Unix timestamp |

### cache_locks
| Column | Type | Notes |
| --- | --- | --- |
| key | string | Primary key |
| owner | string |  |
| expiration | integer | Unix timestamp |

### jobs
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| queue | string | Indexed |
| payload | longText |  |
| attempts | unsignedTinyInteger |  |
| reserved_at | unsignedInteger | Nullable |
| available_at | unsignedInteger |  |
| created_at | unsignedInteger |  |

### job_batches
| Column | Type | Notes |
| --- | --- | --- |
| id | string | Primary key |
| name | string |  |
| total_jobs | integer |  |
| pending_jobs | integer |  |
| failed_jobs | integer |  |
| failed_job_ids | longText |  |
| options | mediumText | Nullable |
| cancelled_at | integer | Nullable |
| created_at | integer |  |
| finished_at | integer | Nullable |

### failed_jobs
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| uuid | string | Unique |
| connection | text |  |
| queue | text |  |
| payload | longText |  |
| exception | longText |  |
| failed_at | timestamp | Defaults to current timestamp |

### personal_access_tokens
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| tokenable_type | string | Part of `morphs` |
| tokenable_id | bigint | Part of `morphs` |
| name | text |  |
| token | string(64) | Unique |
| abilities | text | Nullable |
| last_used_at | timestamp | Nullable |
| expires_at | timestamp | Nullable, indexed |
| created_at | timestamp |  |
| updated_at | timestamp |  |

## Access Control & Identity

### roles
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| name | string | Unique (seeded with `admin`, `Member`) |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### users
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| role_id | foreignId | References `roles.id`, default 2 |
| name | string |  |
| email | string | Unique, indexed |
| password | string | Nullable |
| avatar | string | Nullable |
| cpf_cnpj | string | Nullable |
| phone | string | Nullable |
| remember_token | string | Nullable |
| deleted_at | timestamp | Nullable (soft deletes) |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### password_reset_tokens
| Column | Type | Notes |
| --- | --- | --- |
| email | string | Primary key |
| token | string |  |
| created_at | timestamp | Nullable |

### sessions
| Column | Type | Notes |
| --- | --- | --- |
| id | string | Primary key |
| user_id | foreignId | Nullable, indexed, references `users.id` |
| ip_address | string(45) | Nullable |
| user_agent | text | Nullable |
| payload | longText |  |
| last_activity | integer | Indexed |

## Catalog Structure

### products
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| name | string | Unique |
| eduzz_id | string | Unique |
| slug | string |  |
| cover | string | Nullable |
| description | text | Nullable |
| redirect_url | string | Nullable |
| featured | boolean | Default `false` |
| position | integer | Default `0` |
| deleted_at | timestamp | Nullable (soft deletes) |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### tracks
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| name | string |  |
| description | text | Nullable |
| deleted_at | timestamp | Nullable (soft deletes) |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### courses
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| name | string |  |
| slug | string |  |
| description | text | Nullable |
| cover | string | Nullable |
| duration | integer | Nullable |
| deleted_at | timestamp | Nullable (soft deletes) |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### modules
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| course_id | foreignId | References `courses.id` |
| name | string |  |
| description | text |  |
| position | integer |  |
| duration | integer | Nullable |
| deleted_at | timestamp | Nullable (soft deletes) |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### lessons
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| module_id | foreignId | References `modules.id` |
| panda_id | string | Nullable |
| panda_player_url | string | Nullable |
| panda_thumbnail_url | string | Nullable |
| transcription_url | string | Nullable |
| name | string |  |
| slug | string |  |
| description | text | Nullable |
| resume | text | Nullable |
| duration | integer | Nullable |
| position | integer |  |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### lesson_materials
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| lesson_id | foreignId | References `lessons.id`, indexed with `position` |
| material_type_id | foreignId | References `material_types.id` |
| title | string |  |
| file | string | Nullable |
| description | text | Nullable |
| position | integer |  |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### material_types
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| name | string |  |
| created_at | timestamp |  |
| updated_at | timestamp |  |

## Enrollment & Progress

### product_track
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| product_id | foreignId | References `products.id`, unique with `track_id`, indexed with `position` |
| track_id | foreignId | References `tracks.id`, unique with `product_id` |
| position | integer |  |
| visibility | enum | `visible` (default) or `hidden` |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### track_course
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| track_id | foreignId | References `tracks.id` |
| course_id | foreignId | References `courses.id` |
| position | integer |  |
| visibility | enum | `visible` (default) or `hidden` |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### product_course
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| product_id | foreignId | References `products.id`, unique with `course_id`, indexed with `position` |
| course_id | foreignId | References `courses.id`, unique with `product_id` |
| position | integer |  |
| visibility | enum | `visible` (default) or `hidden` |
| created_at | timestamp |  |
| updated_at | timestamp |  |

Content hierarchy: `products → product_course → courses → modules → lessons`.
Track hierarchy (optional): `products → product_track → tracks → track_course → courses → modules → lessons`.

### product_user
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| product_id | foreignId | References `products.id`, unique with `user_id` |
| user_id | foreignId | References `users.id`, unique with `product_id`, indexed with `status` |
| starts_at | timestamp | Nullable |
| expires_at | timestamp | Nullable |
| status | enum | `active` (default), `suspended`, `canceled` |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### lesson_statuses
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| lesson_id | foreignId | References `lessons.id`, unique with `user_id` |
| user_id | foreignId | References `users.id`, unique with `lesson_id` |
| product_course_id | foreignId | Nullable, references `product_course.id` |
| started_at | timestamp | Nullable |
| completed_at | timestamp | Nullable |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### ratings
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| lesson_id | foreignId | References `lessons.id`, unique with `user_id` |
| user_id | foreignId | References `users.id`, unique with `lesson_id` |
| product_course_id | foreignId | Nullable, references `product_course.id` |
| stars | unsignedTinyInteger | 1–5 rating |
| comment | text | Nullable |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### comments
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| lesson_id | foreignId | References `lessons.id` |
| user_id | foreignId | References `users.id` |
| parent_id | foreignId | Nullable self-reference to `comments.id` |
| product_course_id | foreignId | Nullable, references `product_course.id` |
| content | text |  |
| status | enum | `pending` (default), `approved`, `rejected` |
| read_at | timestamp | Nullable |
| created_at | timestamp |  |
| updated_at | timestamp |  |

### messages
| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| user_id | foreignId | References `users.id` |
| lesson_id | foreignId | References `lessons.id` |
| role | string | Sender role/context |
| message | text |  |
| created_at | timestamp |  |
| updated_at | timestamp |  |

## Seed Data
- `roles`: `admin`, `Member`
- `users`: default admin user (`admin@teste.com`, password hash generated with `Hash::make('password')`)
