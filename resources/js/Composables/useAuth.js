export default function useAuth(user) {
    const hasRole = (role) => {
        return user.roles.includes(role);
    };

    const hasPermission = (permission) => {
        return user.permissions.includes(permission);
    };

    return { hasRole, hasPermission };
}
