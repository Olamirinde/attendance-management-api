<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberModel;

class MemberController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'member_code' => 'required|string|max:50|unique:members,member_code',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'type' => 'required|in:employee,student',
            'department_or_class' => 'nullable|string|max:255',
            'status' => 'in:active,inactive',
        ]);

        $admin = $request->attributes->get('admin');

        $data = $request->only([
            'member_code',
            'name',
            'email',
            'phone',
            'type',
            'department_or_class',
            'status',
        ]);
        $data['created_by'] = $admin->id;

        $member = MemberModel::create($data);

        return response()->json([
            'message' => 'Member created',
            'member' => $member,
        ], 201);
    }

    public function index(Request $request)
{
    $query = MemberModel::query();

    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('member_code', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    if ($request->filled('type')) {
        $query->where('type', $request->input('type'));
    }

    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }

    if ($request->filled('department_or_class')) {
        $query->where('department_or_class', $request->input('department_or_class'));
    }

    $perPage = (int) $request->input('per_page', 10);
    if ($perPage < 1) {
        $perPage = 10;
    }
    if ($perPage > 100) {
        $perPage = 100;
    }

    $members = $query->orderBy('name')->paginate($perPage);

    return response()->json($members);
}

public function show($id)
{
    $member = MemberModel::find($id);

    if (!$member) {
        return response()->json(['message' => 'Member not found'], 404);
    }

    return response()->json(['member' => $member]);
}

public function update(Request $request, $id)
{
    $member = MemberModel::find($id);

    if (!$member) {
        return response()->json(['message' => 'Member not found'], 404);
    }

    $this->validate($request, [
        'member_code' => 'sometimes|required|string|max:50|unique:members,member_code,' . $member->id,
        'name' => 'sometimes|required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:30',
        'type' => 'sometimes|required|in:employee,student',
        'department_or_class' => 'nullable|string|max:255',
        'status' => 'sometimes|required|in:active,inactive',
    ]);

    $data = $request->only([
        'member_code',
        'name',
        'email',
        'phone',
        'type',
        'department_or_class',
        'status',
    ]);

    $member->update($data);

    return response()->json([
        'message' => 'Member updated',
        'member' => $member,
    ]);
}

public function destroy($id)
{
    $member = MemberModel::find($id);

    if (!$member) {
        return response()->json(['message' => 'Member not found'], 404);
    }

    $member->delete();

    return response()->json(['message' => 'Member deleted']);
}
}