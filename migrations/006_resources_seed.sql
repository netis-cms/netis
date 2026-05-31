--
--  Seed resources
-- ----------------
INSERT INTO resources (resource, privilege, description) VALUES
    ('Backend:AccessControl', 'roles-read',           'View roles'),
    ('Backend:AccessControl', 'roles-write',          'Roles management'),
    ('Backend:AccessControl', 'users-read',           'View user roles'),
    ('Backend:AccessControl', 'users-write',          'Assign roles to users'),
    ('Backend:AccessControl', 'permissions-read',   'View permissions'),
    ('Backend:AccessControl', 'permissions-write',  'Manage permissions');
